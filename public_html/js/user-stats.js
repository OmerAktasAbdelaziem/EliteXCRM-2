// User Stats Dashboard JavaScript
// Clean implementation to fix syntax errors

// Global variables
let updateInterval;
let lastUpdateTime = Date.now();

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    initializeRealTimeUpdates();
    requestNotificationPermission();
    initializeUserSelectionCollapse();
    loadLatestNotification();
});

// Real-time Updates System
function initializeRealTimeUpdates() {
    if (
        document.getElementById("days") &&
        document.getElementById("days").value === "1"
    ) {
        updateInterval = setInterval(checkForUpdates, 30000);
        updateLiveIndicator();
    }
}

function checkForUpdates() {
    const selectedUserIds = getSelectedUserIds();
    const commentsMin = document.getElementById("comments_min")?.value || "";
    const commentsMax = document.getElementById("comments_max")?.value || "";

    $.ajax({
        url: "/user-stats/live-updates",
        method: "GET",
        data: {
            user_ids: selectedUserIds,
            comments_min: commentsMin,
            comments_max: commentsMax,
            last_update: lastUpdateTime,
        },
        success: function (response) {
            if (response.hasUpdates) {
                handleLiveUpdates(response);
                lastUpdateTime = Date.now();
            }
        },
        error: function (xhr, status, error) {
            console.log("Live updates error:", error);
        },
    });
}

function getSelectedUserIds() {
    const hiddenInputs = document.querySelectorAll(
        '#hiddenInputs input[name="user_ids[]"]'
    );
    return Array.from(hiddenInputs).map((input) => input.value);
}

// User Selection Functions
function toggleAllUsers() {
    const allUsersCheckbox = document.getElementById("all_users");
    const userCheckboxes = document.querySelectorAll(".user-checkbox");
    const selectedDisplay = document.getElementById("selectedUsersDisplay");
    const hiddenInputs = document.getElementById("hiddenInputs");

    if (allUsersCheckbox.checked) {
        userCheckboxes.forEach((checkbox) => {
            checkbox.checked = false;
        });

        selectedDisplay.innerHTML =
            '<div class="text-center py-4"><i class="bx bx-group text-muted" style="font-size: 2rem;"></i><p class="text-muted mb-0">All Users Selected</p></div>';
        hiddenInputs.innerHTML = "";
    }
}

function handleUserSelection(userId, username, fullName) {
    const userCheckbox = document.getElementById(`user_${userId}`);
    const allUsersCheckbox = document.getElementById("all_users");

    allUsersCheckbox.checked = false;

    if (userCheckbox.checked) {
        addUserToDisplay(userId, username);

        const hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "user_ids[]";
        hiddenInput.value = userId;
        hiddenInput.id = `hidden_${userId}`;
        document.getElementById("hiddenInputs").appendChild(hiddenInput);
    } else {
        removeUserFromDisplay(userId);
    }

    updateSelectedDisplay();
}

function addUserToDisplay(userId, username) {
    const selectedDisplay = document.getElementById("selectedUsersDisplay");

    const allUsersText = selectedDisplay.querySelector(".text-center");
    if (allUsersText) {
        allUsersText.remove();
    }

    const userBadge = document.createElement("div");
    userBadge.className = "badge bg-primary me-1 mb-2 p-2";
    userBadge.id = `selected_${userId}`;
    userBadge.innerHTML = `${username} <button type="button" class="btn-close btn-close-white ms-2" style="font-size: 0.7rem;" onclick="removeUser(${userId})"></button>`;

    selectedDisplay.appendChild(userBadge);
}

function removeUser(userId) {
    document.getElementById(`user_${userId}`).checked = false;
    removeUserFromDisplay(userId);
    updateSelectedDisplay();
}

function removeUserFromDisplay(userId) {
    const userBadge = document.getElementById(`selected_${userId}`);
    const hiddenInput = document.getElementById(`hidden_${userId}`);

    if (userBadge) userBadge.remove();
    if (hiddenInput) hiddenInput.remove();
}

function updateSelectedDisplay() {
    const selectedDisplay = document.getElementById("selectedUsersDisplay");
    const userBadges = selectedDisplay.querySelectorAll(".badge");

    if (userBadges.length === 0) {
        selectedDisplay.innerHTML =
            '<div class="text-center py-3"><i class="bx bx-group text-muted" style="font-size: 1.5rem;"></i><p class="text-muted mb-0 small">All Users Selected</p></div>';
    }
}

// Modal Functions
function showClientDetails(userId, status, days, username) {
    const modal = new bootstrap.Modal(
        document.getElementById("clientDetailsModal")
    );
    const modalTitle = document.getElementById("clientDetailsModalLabel");
    const modalBody = document.querySelector("#clientDetailsModal .modal-body");

    modalTitle.textContent = `${status} Clients - ${username}`;
    modalBody.innerHTML =
        '<div class="text-center p-4"><i class="bx bx-loader-alt bx-spin" style="font-size: 2rem;"></i><p class="mt-2">Loading client details...</p></div>';

    modal.show();

    $.ajax({
        url: `/user-stats/client-details/${userId}/${encodeURIComponent(
            status
        )}`,
        method: "GET",
        data: { days: days },
        success: function (response) {
            modalBody.innerHTML = response;
        },
        error: function () {
            modalBody.innerHTML =
                '<div class="alert alert-danger">Error loading client details.</div>';
        },
    });
}

// Notification Functions
function requestNotificationPermission() {
    if ("Notification" in window && Notification.permission === "default") {
        Notification.requestPermission();
    }
}

function showNotificationToast(title, message, isHtml = false) {
    // Simple toast implementation
    const toast = document.createElement("div");
    toast.className = "toast-notification";
    toast.style.cssText =
        "position: fixed; top: 20px; right: 20px; background: #007bff; color: white; padding: 15px; border-radius: 5px; z-index: 9999;";
    toast.innerHTML = `<strong>${title}</strong><br>${message}`;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 5000);
}

// Filter Functions
function setCommentsFilter(min, max) {
    document.getElementById("comments_min").value = min;
    document.getElementById("comments_max").value = max === null ? "" : max;
}

function resetFilters() {
    window.location.href = window.location.pathname;
}

// Client Transfer Functions
function clearClientSelection() {
    document.querySelectorAll(".client-checkbox").forEach((checkbox) => {
        checkbox.checked = false;
    });

    const selectAllCheckbox = document.getElementById("selectAllClients");
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    }

    const transferSection = document.getElementById("clientTransferSection");
    if (transferSection) {
        transferSection.style.display = "none";
    }

    const transferToUser = document.getElementById("transferToUser");
    if (transferToUser) {
        transferToUser.value = "";
    }

    const selectedCount = document.getElementById("selectedClientCount");
    if (selectedCount) {
        selectedCount.textContent = "0";
    }
}

function handleClientCheckboxChange() {
    const selectedCheckboxes = document.querySelectorAll(
        ".client-checkbox:checked"
    );
    const selectedCount = selectedCheckboxes.length;

    const countElement = document.getElementById("selectedClientCount");
    if (countElement) {
        countElement.textContent = selectedCount;
    }

    const transferSection = document.getElementById("clientTransferSection");
    if (transferSection) {
        transferSection.style.display = selectedCount > 0 ? "block" : "none";
    }

    const selectAllCheckbox = document.getElementById("selectAllClients");
    const allCheckboxes = document.querySelectorAll(".client-checkbox");

    if (selectAllCheckbox && allCheckboxes.length > 0) {
        if (selectedCount === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (selectedCount === allCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }
}

function toggleAllClientSelection() {
    const selectAllCheckbox = document.getElementById("selectAllClients");
    const clientCheckboxes = document.querySelectorAll(".client-checkbox");

    if (selectAllCheckbox && clientCheckboxes.length > 0) {
        clientCheckboxes.forEach((checkbox) => {
            checkbox.checked = selectAllCheckbox.checked;
        });

        handleClientCheckboxChange();
    }
}

// Initialize live indicator
function updateLiveIndicator() {
    const indicator = document.querySelector(".live-indicator");
    if (indicator) {
        setInterval(() => {
            indicator.style.animation = "none";
            setTimeout(() => {
                indicator.style.animation = "pulse 2s infinite";
            }, 10);
        }, 10000);
    }
}

// User Selection Collapse
function initializeUserSelectionCollapse() {
    const collapseElement = document.getElementById("userSelectionCollapse");
    const toggleButton = document.querySelector(
        '[data-bs-target="#userSelectionCollapse"]'
    );

    if (collapseElement && toggleButton) {
        collapseElement.addEventListener("show.bs.collapse", function () {
            toggleButton.innerHTML =
                '<i class="bx bx-chevron-up me-1"></i> Hide Users';
        });

        collapseElement.addEventListener("hide.bs.collapse", function () {
            toggleButton.innerHTML =
                '<i class="bx bx-chevron-down me-1"></i> Show Users';
        });
    }
}
