document.addEventListener('DOMContentLoaded', function() {
    const chatHistory = document.querySelector('.chat-history');
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const scrollToBottomButton = document.getElementById('scroll-to-top');
    let userIsScrollingUp = false; // Biến để kiểm tra người dùng cuộn lên
    function scrollToBottom(smooth = true)
    {
        if (chatHistory)
        {
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }
    }


    chatHistory.addEventListener('scroll', function() {
        // Kiểm tra nếu người dùng cuộn lên và đang cách cuối trang ít hơn 300px
        if (chatHistory.scrollTop < chatHistory.scrollHeight - chatHistory.clientHeight - 5000) {
            scrollToBottomButton.style.display = 'block'; // Hiển thị mũi tên cuộn xuống
        } else {
            scrollToBottomButton.style.display = 'none'; // Ẩn mũi tên khi đang ở cuối trang
        }
    });
    function scrollBtn(smooth = true) {
        if (chatHistory) {
            if (smooth) {
                chatHistory.scroll({
                    top: chatHistory.scrollHeight,
                    behavior: 'smooth'
                });
            } else {
                chatHistory.scrollTop = chatHistory.scrollHeight;
            }
        }
    }
    // Sự kiện click vào mũi tên cuộn xuống
    scrollToBottomButton.addEventListener('click', function() {
        scrollBtn();
    });

    // Cuộn xuống dưới khi tải trang ban đầu
    scrollToBottom();

    // Cuộn đến trường nhập liệu
    if (messageInput) {
        messageInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        messageInput.focus();
    }

    // Theo dõi khi người dùng cuộn trong lịch sử trò chuyện
    chatHistory.addEventListener('scroll', function() {
        // Kiểm tra xem người dùng có cuộn lên trên không
        if (chatHistory.scrollTop < chatHistory.scrollHeight - chatHistory.offsetHeight - 50) {
            userIsScrollingUp = true; // Người dùng đang cuộn lên
        } else {
            userIsScrollingUp = false; // Người dùng đã cuộn xuống dưới
        }
    });

    // Hàm để cuộn xuống cuối chỉ khi người dùng không cuộn lên
    function scrollOnNewMessage() {
        if (!userIsScrollingUp) {
            scrollToBottom();
        }
    }

    // Tùy chọn: Mutation Observer để phát hiện các thông báo mới
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length) {
                scrollOnNewMessage();
            }
        });
    });

    // Bắt đầu quan sát danh sách tin nhắn trò chuyện
    if (chatMessages) {
        observer.observe(chatMessages, {
            childList: true,
            subtree: true
        });
    }
});




document.querySelectorAll('.message-item').forEach(item => {
    item.addEventListener('click', function () {
        // Lấy phần tử thời gian trong tin nhắn hiện tại
        const timeElement = this.querySelector('.message-data-time');
        
        // Nếu thời gian chưa hiển thị, thì ẩn tất cả thời gian của các tin nhắn khác
        if (timeElement.style.display === 'none' || timeElement.textContent === '') {
            // Ẩn tất cả thời gian của các tin nhắn khác
            document.querySelectorAll('.message-data-time').forEach(time => {
                time.style.display = 'none';
            });

            // Lấy thời gian từ data-time
            const timeString = this.getAttribute('data-time');
            const date = new Date(timeString);

            // Định dạng thời gian theo ý muốn
            const formattedTime = date.toLocaleTimeString([], { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit' 
            });

            // Gán và hiển thị thời gian cho tin nhắn hiện tại
            timeElement.textContent = formattedTime; // Gán thời gian
            timeElement.style.display = 'inline';   // Hiển thị
        } else {
            // Nếu thời gian đang hiển thị thì ẩn đi
            timeElement.style.display = 'none';
        }
    });
});



