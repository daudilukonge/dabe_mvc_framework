// Chat functionality JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.querySelector('.chat-messages');
    const chatInput = document.querySelector('.chat-input input');
    const sendBtn = document.querySelector('.send-btn');
    
    if (sendBtn && chatInput) {
        sendBtn.addEventListener('click', sendMessage);

        // send message on Enter key press
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }
    
    /**
     * Function to send a message
     */
    function sendMessage() {

        const messageText = chatInput.value.trim();

        if (messageText) {

            // Create new message element
            const messageDiv = document.createElement('div'); 
            messageDiv.className = 'message sent';
            messageDiv.innerHTML = `
                <div class="message-content">
                    <p>${messageText}</p>
                </div>
                <span class="message-time">${getCurrentTime()}</span>
            `;
            
            // Add to chat
            chatMessages.appendChild(messageDiv);
            chatInput.value = '';
            
            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            // In a real app, you would send the message to the server here

        }

    }
    

    /**
     * Function to get the current time
     */
    function getCurrentTime() {
        const now = new Date();
        let hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        return `${hours}:${minutes} ${ampm}`;
    }
    
    // Simulate receiving a message (demo only)
    if (chatMessages) {
        setTimeout(() => {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message received';
            messageDiv.innerHTML = `
                <div class="message-content">
                    <p>Thanks for your message! Here's a sample file.</p>
                    <div class="file-preview">
                        <div class="file-icon doc"></div>
                        <div class="file-info">
                            <h5>sample-document.docx</h5>
                            <p>1.2 MB</p>
                        </div>
                        <a href="#" class="download-btn">Download</a>
                    </div>
                </div>
                <span class="message-time">${getCurrentTime()}</span>
            `;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 2000);
    }
});