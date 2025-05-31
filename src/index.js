import {createRoot} from '@wordpress/element';
import ChatBubble from "./components/ChatBubble.jsx";

document.addEventListener('DOMContentLoaded', () => {
	document.body.innerHTML = '<div id="app"></div>';

	const target = document.getElementById('app');

	if (target) {
		const root = createRoot(target);
		root.render(<ChatBubble/>);
	}
})
