import { useState, useEffect } from "react";

const useOpeningHours = () => {
	const [openingHours, setOpeningHours] = useState({
		opening_hour: 10,
		closing_hour: 16
	});

	const fetchOpeningHours = async () => {
		try {
			const response = await fetch('/api/opening');
			if (response.ok) {
				const data = await response.json();
				setOpeningHours(data);
			} else {
				const data = await response.json();
				console.log(data);
			}
		} catch (error) {
			console.log(error);
		}
	};

	useEffect(() => {
		fetchOpeningHours();
	}, []);

	return openingHours;
};

export default useOpeningHours;

