import { useState, useEffect, useRef } from 'react';
import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import EventFormModal from '../EventFormModal';
import { currentViewDates, generatedRecurringEvents, formatTime, compileEvent } from '../../utils/calendarUtils';
import useOpeningHours from '../hooks/useOpeningHours';

const Calendar = () => {

	const calendarRef = useRef(null);

	// States
	const [events, setEvents] = useState([]);
	const [modalOpen, setModalOpen] = useState(false);
	const [initialEventData, setInitialEventData] = useState({
		start_date: '',
		end_date: '',
		start_time: '',
		end_time: '',
		repetition: '',
		day: '',
		user: ''
	});
	const openingHours = useOpeningHours();

	async function fetchBookings() {
		try {
			const response = await fetch('/api/bookings');
			if (response.ok) {
				const data = await response.json();
				const generatedEvents = generatedRecurringEvents(data, currentViewDates(calendarRef));
				setEvents(generatedEvents);
			} else {
				const data = await response.json();
				console.log(data)
			}
		} catch (error) {
			console.log(error);
		}
	}

	const handleDateSelect = (selectInfo) => {
		const initialEvent = compileEvent(selectInfo);

		setInitialEventData({
			...initialEventData,
			start_date: initialEvent.start_date,
			end_date: initialEvent.end_date,
			start_time: initialEvent.startTime,
			end_time: initialEvent.endTime,
			day: initialEvent.day,
			repetition: 'no',
			user: ''
		})
    setModalOpen(true);
  }

	const handleFormSubmit = async (eventData) => {
		setModalOpen(false);
		try {
			const response = await fetch('/api/bookings', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(eventData)
			});
			if (response.ok) {
				await fetchBookings();
				alert("The booking is successfully saved.")
			} else {
				const errorData = await response.json();
				alert("Bad request: \n" + errorData.message)
			}
		} catch (error) {
			console.log(error);
		}
	}

	return (
		<div>
			<FullCalendar 
				ref={calendarRef}
				plugins={[dayGridPlugin, timeGridPlugin, interactionPlugin]} 
				initialView={'dayGridMonth'}
				firstDay={1}
				weekends={false}
				headerToolbar={{
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,timeGridWeek,timeGridDay'
				}}
				height={"90vh"}
				events={events}
				datesSet={fetchBookings}
				select={handleDateSelect}
				selectable={true}
				slotMinTime={formatTime(openingHours.opening_hour)}
        slotMaxTime={formatTime(openingHours.closing_hour)}
			/>
			{ modalOpen && (
				<EventFormModal
					setModalOpen={setModalOpen}
					onSubmit={handleFormSubmit}
					initialData={initialEventData}
				/>
			)}
		</div>
	)
}

export default Calendar