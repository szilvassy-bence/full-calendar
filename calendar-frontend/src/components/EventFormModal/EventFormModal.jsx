import { useState } from 'react';
import './EventFormModal.css'

const EventFormModal  = ({ setModalOpen, initialData, onSubmit }) => {

	const [formData, setFormData] = useState(initialData);

	const handleChange = (e) => {
		const {name, value} = e.target;
		setFormData({...formData, [name]: value});
	}

	const handleSubmit = (e) => {
		e.preventDefault();
		onSubmit(formData);
	}
	
	const handleModalClick = (e) => {
    e.stopPropagation(); 
  };

	return (
		<>
		{ formData && (
			<div id="modal-backdrop" onClick={() => setModalOpen(false)}>
				<div id="modal" onClick={handleModalClick}>
					<div id="modal-header">
						<h2>Create Event</h2>
						<svg 
							onClick={() => setModalOpen(false)}
							className="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
							<path fillRule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm7.707-3.707a1 1 0 0 0-1.414 1.414L10.586 12l-2.293 2.293a1 1 0 1 0 1.414 1.414L12 13.414l2.293 2.293a1 1 0 0 0 1.414-1.414L13.414 12l2.293-2.293a1 1 0 0 0-1.414-1.414L12 10.586 9.707 8.293Z" clipRule="evenodd"/>
						</svg>
					</div>
						<form id="modal-form" onSubmit={handleSubmit}>
								<label>
										<span>User:</span>
										<input
												type="text"
												name="user"
												value={formData.user}
												onChange={handleChange}
												required
										/>
								</label>
								<label>
										<span>Start Date:</span>
										<input
												type="date"
												name="start_date"
												value={formData.start_date}
												onChange={handleChange}
												required
										/>
								</label>
								<label>
										<span>End Date:</span>
										<input
												type="date"
												name="end_date"
												value={formData.end_date}
												onChange={handleChange}
										/>
								</label>
								<label>
										<span>Start Time:</span>
										<input
												type="time"
												name="start_time"
												value={formData.start_time}
												onChange={handleChange}
												required
										/>
								</label>
								<label>
									<span>End Time:</span>
										<input
												type="time"
												name="end_time"
												value={formData.end_time}
												onChange={handleChange}
												required
										/>
								</label>
								<label>
									<span>Day:</span>
										<select name="day" value={formData.day} onChange={handleChange} required>
												<option value="monday">Monday</option>
												<option value="tuesday">Tuesday</option>
												<option value="wednesday">Wednesday</option>
												<option value="thursday">Thursday</option>
												<option value="friday">Friday</option>
												<option value="saturday">Saturday</option>
												<option value="sunday">Sunday</option>
										</select>
								</label>
								<label>
									<span>Repetition:</span>
										<select name="repetition" value={formData.repetition} onChange={handleChange} required>
												<option value="no">No</option>
												<option value="weeks">Every Week</option>
												<option value="odd_weeks">Odd Weeks</option>
												<option value="even_weeks">Even Weeks</option>
										</select>
								</label>
								<button type="submit">Create Event</button>
						</form>
				</div>
			</div>
		)}
		</>
	);
}

export default EventFormModal