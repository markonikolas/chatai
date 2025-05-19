import {createRoot, createElement} from '@wordpress/element';
import ChatBubble from "./components/ChatBubble";

document.addEventListener('DOMContentLoaded', () => {
	document.body.innerHTML = '<div id="app"></div>';

	const target = document.getElementById('app');

	if (target) {
		const root = createRoot(target);
		root.render(<ChatBubble/>);
	}
})
