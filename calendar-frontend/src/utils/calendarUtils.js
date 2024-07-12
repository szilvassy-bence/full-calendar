import { set, formatISO, startOfWeek, addWeeks, addDays, getWeek } from 'date-fns';

export const currentViewDates = (calendarRef) => {
	const calendarApi = calendarRef.current.getApi();
	const view = calendarApi.view;
	const start = view.activeStart;
	const end = view.activeEnd;
	return { start, end};
}

export const generatedRecurringEvents = (data, {start, end}) => {
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

export const setTime = (date, time) => {
	const [hours, minutes] = time.split(":");
	return set(date, {hours, minutes} );
}

export const getDayIndex = (day) => {
	const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
	return days.indexOf(day.toLowerCase());
}

export const isEvenWeek = (date) => {
	const weekNumber = getWeek(date);
	return weekNumber % 2 === 0;
}

export const formatTime = (hour) => {
	return hour < 10 ? `0${hour}:00:00` : `${hour}:00:00`;
}

export const compileEvent = (selectInfo) => {
	const {allDay, startStr, endStr, start, end} = selectInfo;
		const start_date = allDay ? new Date(startStr) : startStr;
		const end_date = allDay ? addDays(new Date(endStr), -1) : endStr;
		const startTime = allDay ? '08:00' : start.toTimeString().substring(0, 5);
		const endTime = allDay ? '09:00' : end.toTimeString().substring(0, 5);
		const day = new Date(startStr).toLocaleString('en-us', {weekday: 'long'}).toLowerCase();
		return { 
			start_date: formatISO(start_date).split('T')[0],	
			end_date: formatISO(end_date).split('T')[0],	
			startTime, 
			endTime,	
			day	}
}