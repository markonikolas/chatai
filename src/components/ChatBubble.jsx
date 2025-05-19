import React, {useState} from 'react';

export default function ChatBubble() {
	const [isOpen, setIsOpen] = useState(false);
	const [messages, setMessages] = useState([]);
	const [input, setInput] = useState('');
	const [loading, setLoading] = useState(false);

	const toggleChat = () => setIsOpen(!isOpen);

	const styles = {
		wrapper: {
			position: 'fixed',
			bottom: '20px',
			right: '20px',
			zIndex: 9999,
		},
		toggleButton: {
			background: '#0073aa',
			color: '#ffffff',
			border: 'none',
			borderRadius: '50%',
			width: '50px',
			height: '50px',
			fontSize: '20px',
			cursor: 'pointer',
			boxShadow: '0 2px 5px rgba(0,0,0,0.2)',
		},
		panel: {
			marginTop: '10px',
			width: '300px',
			maxHeight: '400px',
			background: '#ffffff',
			borderRadius: '8px',
			boxShadow: '0 2px 10px rgba(0,0,0,0.2)',
			display: 'flex',
			flexDirection: 'column',
		},
		messages: {
			padding: '10px',
			overflowY: 'auto',
			flexGrow: 1,
			display: 'flex',
			flexDirection: 'column',
			gap: '6px',
		},
		message: {
			padding: '8px 12px',
			borderRadius: '16px',
			maxWidth: '80%',
		},
		inputWrapper: {
			display: 'flex',
			borderTop: '1px solid #ddd',
		},
		input: {
			flex: 1,
			padding: '10px',
			border: 'none',
			outline: 'none',
		},
		sendButton: {
			background: '#0073aa',
			color: '#ffffff',
			border: 'none',
			padding: '10px 16px',
			cursor: 'pointer',
		},
	};

	const sendMessage = async () => {
		if (!input.trim()) return;

		const userMessage = {role: 'user', content: input};
		setMessages([...messages, userMessage]);
		setInput('');
		setLoading(true);

		try {
			const res = await fetch('/wp-json/chatai/v1/ask', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({question: input}),
			});

			const data = await res.json();
			const botMessage = {role: 'assistant', content: data.answer ?? 'No response.'};
			setMessages((prev) => [...prev, botMessage]);
		}
		catch (e) {
			setMessages((prev) => [
				...prev,
				{role: 'assistant', content: 'Error contacting ChatGPT.'},
			]);
		}
		finally {
			setLoading(false);
		}
	};

	return (
		<div className="chat-bubble-wrapper" style={styles.wrapper}>
			<button onClick={toggleChat} style={styles.toggleButton}>
				💬
			</button>

			{isOpen && (
				<div style={styles.panel}>
					<div style={styles.messages}>
						{messages.map((msg, idx) => (
							<div
								key={idx}
								style={{
									...styles.message,
									alignSelf: msg.role === 'user' ? 'flex-end' : 'flex-start',
									background: msg.role === 'user' ? '#dcf8c6' : '#F1F0F0',
								}}
							>
								{msg.content}
							</div>
						))}
						{loading && <div style={styles.message}>Typing...</div>}
					</div>
					<div style={styles.inputWrapper}>
						<input
							type="text"
							value={input}
							onChange={(e) => setInput(e.target.value)}
							onKeyDown={(e) => e.key === 'Enter' && sendMessage()}
							style={styles.input}
							placeholder="Ask me something..."
						/>
						<button onClick={sendMessage} style={styles.sendButton}>➤</button>
					</div>
				</div>
			)}
		</div>
	);
}
