import { useState, useEffect } from 'react';
import { generatedRecurringEvents, currentViewDates } from '../../utils/calendarUtils';

const useBookings = (calendarRef) => {
	const [events, setEvents] = useState([]);

	const fetchBookings = async () => {
		try {
			const response = await fetch('/api/bookings');
			if (response.ok) {
				const data = await response.json();
				const generatedEvents = generatedRecurringEvents(data, currentViewDates(calendarRef));
				setEvents(generatedEvents);
			} else {
				const data = await response.json();
				console.log(data);
			}
		} catch (error) {
			console.log(error);
		}
	}

	useEffect(() => {
		fetchBookings();
	}, []);

	return { events, fetchBookings };
}

export default useBookings;