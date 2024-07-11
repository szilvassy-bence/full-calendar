import { useState, useEffect, useRef } from 'react'

import FullCalendar from '@fullcalendar/react'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import { set, formatISO, startOfWeek, addWeeks, addDays, getWeek } from 'date-fns';
import EventFormModal from '../EventFormModal';

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
	const [openingHours, setOpeningHours] = useState({
		opening_hour: 10,
		closing_hour: 16
	});

	useEffect(() => {
		async function fetchOpeningHours() {
			try {
				const response = await fetch('/api/opening');
				if (response.ok) {
					const data = await response.json();
					setOpeningHours(data);
				} else {
					const data = await response.json();
					console.log(data)
				}
			} catch (error) {
				console.log(error)
			}
		}
		fetchOpeningHours();
	}, [])

	async function fetchBookings() {
		try {
			const response = await fetch('/api/bookings');
			if (response.ok) {
				const data = await response.json();
				const generatedEvents = generatedRecurringEvents(data, currentViewDates());
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
		console.log(selectInfo);
		const {allDay, startStr, endStr, start, end} = selectInfo;
		const start_date = allDay ? new Date(startStr) : startStr;
		const end_date = allDay ? addDays(new Date(endStr), -1) : endStr;
		const startTime = allDay ? '08:00' : start.toTimeString().substring(0, 5);
		const endTime = allDay ? '09:00' : end.toTimeString().substring(0, 5);
		const day = new Date(startStr).toLocaleString('en-us', {weekday: 'long'}).toLowerCase();

		setInitialEventData({
			...initialEventData,
			start_date: formatISO(start_date).split('T')[0],
			end_date: formatISO(end_date).split('T')[0],
			start_time: startTime,
			end_time: endTime,
			day,
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
				alert("Bad booking: " + errorData.message)
			}
		} catch (error) {
			console.log(error);
		}
	}

	const currentViewDates = () => {
    const calendarApi = calendarRef.current.getApi();
    const view = calendarApi.view;
    const start = view.activeStart;
    const end = view.activeEnd;
    return { start, end};
  }

	const generatedRecurringEvents = (data, {start, end}) => {
		const events = [];
		data.forEach(event => {
			let startDate = new Date(event.start_date);
			startDate.setHours(0, 0, 0, 0);

			let endDate = event.end_date ? new Date(event.end_date) : addWeeks(start, 52);
			endDate.setHours(0, 0, 0, 0);

			const eventDayIndex = getDayIndex(event.day);

			if (event.repetition === 'no') {
				if (startDate >= start && startDate <= end) {
					const eventStart = setTime(startDate, event.start_time);
					const eventEnd = setTime(startDate, event.end_time);
					events.push({
						title: event.user,
						start: formatISO(eventStart),
						end: formatISO(eventEnd)
					})
				}
			} else {
				let current = startOfWeek(start, {weekStartsOn: 1});

				while (current <= end && current <= endDate) {
					const eventDay = addDays(current, (eventDayIndex - current.getDay() + 7) % 7);

					if (eventDay >= startDate && eventDay >= start && eventDay <= end && eventDay <= endDate) {
						if (
							(event.repetition === 'weeks') ||
							(event.repetition === 'odd_weeks' && !isEvenWeek(current)) ||
							(event.repetition === 'even_weeks' && isEvenWeek(current))
						) {
							const eventStart = setTime(eventDay, event.start_time);
							const eventEnd = setTime(eventDay, event.end_time);
							events.push({
								title: event.user,
								start: formatISO(eventStart),
								end: formatISO(eventEnd),
							})
						}
					}
					current = addWeeks(current, 1);
				}
			}
		})
		return events;
	}

	const setTime = (date, time) => {
		const [hours, minutes] = time.split(":");
		return set(date, {hours, minutes} );
	}

	const getDayIndex = day => {
		const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    return days.indexOf(day.toLowerCase());
	}

	const isEvenWeek = date => {
		const weekNumber = getWeek(date);
		return weekNumber % 2 === 0;
	}

	const formatTime = hour => {
		return hour < 10 ? `0${hour}:00:00` : `${hour}:00:00`;
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