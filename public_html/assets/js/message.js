$(document).ready(function() {
    $('.chat-list a').click(function() {
        $('.chat-list a.active').removeClass('active');
        $(this).addClass('active');
    });
});

// Get the search input element
const searchInput = document.querySelector('.form-control');

// Get the chat list element
const chatList = document.querySelector('.chat-list');

// Get the list items within the chat list
const chatItems = chatList.querySelectorAll('.list-group-item');

// Add event listener to search input
searchInput.addEventListener('input', () => {
// Get the search query from the input field
const searchQuery = searchInput.value.toLowerCase();

// Filter the relevant data based on the search query
const filteredItems = Array.from(chatItems).filter((item) => {
const chatTitle = item.querySelector('.chat-title').textContent.toLowerCase();
const chatMsg = item.querySelector('.chat-msg').textContent.toLowerCase();
return chatTitle.includes(searchQuery) || chatMsg.includes(searchQuery);
});

// Hide all the chat items and show only the filtered ones
chatItems.forEach((item) => {
item.classList.add('d-none');
});

filteredItems.forEach((item) => {
item.classList.remove('d-none');
});
});


new PerfectScrollbar('.chat-list');
new PerfectScrollbar('.chat-content');