import React from "react";
import { render, screen } from '@testing-library/react';
import Calendar from '../src/components/Calendar';

test('fetch and display events correctly', async() => {
	const mockEvents = [
		{ id: 1, title: 'Event 1', start: '2024-07-01T08:00:00', end: '2024-07-01T09:00:00' },
    { id: 2, title: 'Event 2', start: '2024-07-05T10:00:00', end: '2024-07-05T12:00:00' }
	];

	global.fetch = jest.fn.mockResolvedValue({
		ok: true,
		json: () => Promise.resolve(mockEvents)
	});

	render(<Calendar />);

	const event1 = await screen.findByText('Event 1');
  const event2 = await screen.findByText('Event 2');
  expect(event1).toBeInTheDocument();
  expect(event2).toBeInTheDocument();
})