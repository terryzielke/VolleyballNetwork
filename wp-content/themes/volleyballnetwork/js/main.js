/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/*!*********************!*\
  !*** ./src/main.ts ***!
  \*********************/

/*
  GLOBAL FUNCTIONS
*/
function clickOutsideElement(element, callback, exceptionElement = null) {
    if (!element) {
        return;
    }
    const outsideClickListener = (event) => {
        const target = event.target; // Cast to Node for contains()
        if (element && !element.contains(target) && (!exceptionElement || !exceptionElement.contains(target))) {
            callback();
        }
    };
    document.addEventListener('click', outsideClickListener);
    return () => {
        document.removeEventListener('click', outsideClickListener);
    };
}
/**
 * If page is not at top, add scrolling class to body
 * If page is scrolling up, add scrolling-up class to body
 */
let lastScrollTop = 0;
window.addEventListener('scroll', () => {
    const body = document.body;
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    if (scrollTop > 0) {
        body.classList.add('scrolling');
    }
    else {
        body.classList.remove('scrolling');
    }
    if (scrollTop > lastScrollTop) {
        body.classList.remove('scrolling-up');
    }
    else {
        body.classList.add('scrolling-up');
    }
    lastScrollTop = scrollTop;
});
/*
  SEARCH FORM ANIMATION
*/
const searchForm = document.querySelector('.search-form');
const searchField = document.querySelector('.search-field');
// Check if elements exist
if (searchForm && searchField) {
    searchField.addEventListener('focus', () => {
        searchForm.style.width = '200px';
    });
    searchField.addEventListener('blur', () => {
        searchForm.style.removeProperty("width");
    });
}
/*
  MENU SETUP
*/
const menuButton = document.querySelector("#menu-button");
const primaryMenu = document.querySelector("#primary-menu");
const bars = document.querySelectorAll("#menu-button .bar");
const subMenus = document.querySelectorAll("#primary-menu .sub-menu");
const menuToggles = document.querySelectorAll('#primary-menu .menu-toggle');
function closeMenu() {
    menuToggles.forEach(menuToggle => menuToggle.classList.remove("on"));
    subMenus.forEach(subMenu => subMenu.classList.remove("active"));
    if (primaryMenu) {
        primaryMenu.classList.remove("open");
    }
    if (menuButton) {
        menuButton.classList.remove("active");
    }
    // Enable scrolling
    document.body.style.overflowY = 'auto';
    // Reset menuButton styles
    setTimeout(() => {
        bars.forEach(bar => bar.removeAttribute("style"));
    }, 200);
}
/*
  ANIMATIONS FOR MENU BUTTON
*/
menuToggles.forEach(menuToggle => menuToggle.classList.remove("on"));
if (menuButton && primaryMenu) {
    menuButton.addEventListener("click", () => {
        if (primaryMenu.classList.contains("open")) {
            // Closing animation
            closeMenu();
        }
        else {
            // Opening animation
            primaryMenu.classList.add("open");
            menuButton.classList.add("active");
            // Disable scrolling
            document.body.style.overflowY = 'hidden';
        }
    });
}
if (primaryMenu) {
    const removeClickListener = clickOutsideElement(primaryMenu, () => {
        console.log('Clicked outside the primary menu!');
        closeMenu();
    }, menuButton); // Pass the menuButton as the third argument
}
else {
    console.error("Primary menu element not found.");
}
/*
  MENU EXPANTION ON MOBILE
*/
const menuItems = document.querySelectorAll('.menu-item-has-children');
menuItems.forEach(item => {
    const button = document.createElement('span');
    button.classList.add('menu-toggle');
    const firstLink = item.querySelector('a');
    if (firstLink) {
        item.insertBefore(button, firstLink);
    }
    else {
        item.appendChild(button);
    }
    button.addEventListener('click', (event) => {
        event.preventDefault();
        const firstSubMenu = item.querySelector('.sub-menu');
        if (firstSubMenu) {
            firstSubMenu.classList.toggle('active');
            button.classList.toggle('on'); // Toggle the 'on' class on the button
            // Close other open sub-menus and update button states
            const parentSubMenu = item.parentElement;
            if (parentSubMenu) {
                const siblingMenuItems = Array.from(parentSubMenu.children).filter(child => child !== item && child.classList.contains('menu-item-has-children'));
                siblingMenuItems.forEach(sibling => {
                    const siblingSubMenu = sibling.querySelector('.sub-menu');
                    const siblingButton = sibling.querySelector('.menu-toggle');
                    if (siblingSubMenu && siblingButton) {
                        siblingSubMenu.classList.remove('active');
                        siblingButton.classList.remove('on'); // Remove 'on' class from sibling buttons
                    }
                });
            }
        }
    });
});
/*
  EQUAL HEIGHTS CELLS
*/
function equalizeCellHeights() {
    const cellRows = document.querySelectorAll('.cell-row');
    cellRows.forEach(row => {
        const cells = row.querySelectorAll('.cell');
        let maxHeight = 0;
        cells.forEach(cell => {
            const htmlCell = cell; // Cast to HTMLElement
            htmlCell.style.height = 'auto';
            maxHeight = Math.max(maxHeight, htmlCell.offsetHeight);
        });
        cells.forEach(cell => {
            const htmlCell = cell; // Cast to HTMLElement
            htmlCell.style.height = `${maxHeight}px`;
        });
    });
}
// Call initially on page load
window.addEventListener('DOMContentLoaded', equalizeCellHeights);
// Call on window resize
let resizeTimeout = null;
window.addEventListener('resize', () => {
    if (resizeTimeout) {
        clearTimeout(resizeTimeout);
    }
    resizeTimeout = setTimeout(equalizeCellHeights, 200);
});
/*
  CONTENT IN VIEWPORT
*/
function isFullyInViewportRobust(element) {
    const rect = element.getBoundingClientRect();
    return (rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth));
}
function handleVisibilityCheck() {
    const elements = document.querySelectorAll(".visible"); // Get all elements with the class "visible"
    elements.forEach((element) => {
        if (element instanceof HTMLElement) { // Type guard to ensure it's an HTMLElement
            if (isFullyInViewportRobust(element)) {
                // Do something, e.g., add a class, change styles, etc.
                element.classList.add("is-visible"); // Example: add a class
            }
            else {
                element.classList.remove("is-visible"); // Example: remove the class
            }
        }
    });
}
// Initial check when the page loads:
handleVisibilityCheck();
// Check on scroll:
window.addEventListener('scroll', handleVisibilityCheck);
// Check on resize (important for viewport changes):
window.addEventListener('resize', handleVisibilityCheck);
// Example using Intersection Observer (More performant for frequent changes):
const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        const element = entry.target; // Type assertion
        if (entry.isIntersecting && entry.intersectionRatio === 1) {
            element.classList.add("is-visible");
        }
        else {
            element.classList.remove("is-visible");
        }
    });
}, { threshold: 1 });
const visibleElements = document.querySelectorAll(".visible");
visibleElements.forEach(element => {
    if (element instanceof HTMLElement) { // Type guard
        observer.observe(element);
    }
});
/**
 * On click of #league-filters .league-filter
 * get value from data-league-id attribute
 * Find #league-container .league with matching data-league-id
 * Hide all other .league elements
 * Show the matching .league element
 */
const leagueFilters = document.querySelectorAll('#league-filters .league-filter');
const leagueContainers = document.querySelectorAll('#leagues-section .league');
// click event
leagueFilters.forEach(filter => {
    filter.addEventListener('click', () => {
        // give the clicked filter the active class
        leagueFilters.forEach(filter => filter.classList.remove('active'));
        filter.classList.add('active');
        // get the leagueId from the clicked filter
        const leagueId = filter.getAttribute('data-league-id');
        if (leagueId) {
            // if leagueId is equal to "all", show all leagues
            if (leagueId === 'all') {
                leagueContainers.forEach(league => {
                    const leagueElement = league;
                    leagueElement.style.display = 'block';
                });
            }
            // else, show only the league with the matching leagueId
            else {
                leagueContainers.forEach(league => {
                    const leagueElement = league;
                    if (leagueElement.getAttribute('data-league-id') === leagueId) {
                        leagueElement.style.display = 'block';
                    }
                    else {
                        leagueElement.style.display = 'none';
                    }
                });
            }
        }
    });
});
/**
 * Program search filters
 */
document.addEventListener("DOMContentLoaded", () => {
    const filters = document.querySelectorAll("#filters select");
    applyFilters();
    filters.forEach(filter => {
        filter.addEventListener("change", applyFilters);
    });
    function applyFilters() {
        const getFilterValue = (id) => {
            const element = document.getElementById(id);
            return element ? element.value : "";
        };
        const selectedFilters = {
            state: getFilterValue("state"),
            city: getFilterValue("city"),
            programs: getFilterValue("programs"),
            season: getFilterValue("season"),
            age: getFilterValue("age"),
            gender: getFilterValue("gender")
        };
        const programs = document.querySelectorAll(".program");
        programs.forEach(program => {
            var _a, _b, _c, _d, _e, _f;
            const programElement = program;
            const state = ((_a = programElement.dataset.state) === null || _a === void 0 ? void 0 : _a.trim()) || "";
            const city = ((_b = programElement.dataset.city) === null || _b === void 0 ? void 0 : _b.trim()) || "";
            const programs = ((_c = programElement.dataset.programs) === null || _c === void 0 ? void 0 : _c.trim()) || "";
            const season = ((_d = programElement.dataset.season) === null || _d === void 0 ? void 0 : _d.trim()) || "";
            const ages = ((_e = programElement.dataset.ages) === null || _e === void 0 ? void 0 : _e.split(",").map(age => age.trim())) || [];
            const gender = ((_f = programElement.dataset.gender) === null || _f === void 0 ? void 0 : _f.trim()) || "";
            let matches = true;
            if (selectedFilters.state && state !== selectedFilters.state)
                matches = false;
            if (selectedFilters.city && city !== selectedFilters.city)
                matches = false;
            if (selectedFilters.programs && programs !== selectedFilters.programs)
                matches = false;
            if (selectedFilters.season && season !== selectedFilters.season)
                matches = false;
            if (selectedFilters.age && !ages.includes(selectedFilters.age))
                matches = false;
            if (selectedFilters.gender && gender !== selectedFilters.gender)
                matches = false;
            programElement.style.display = matches ? "block" : "none";
        });
    }
});

/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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QUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLENBQUMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9jb21wbGV0ZS1zYXNzLXR1dG9yaWFsLy4vc3JjL21haW4udHMiXSwic291cmNlc0NvbnRlbnQiOlsiXCJ1c2Ugc3RyaWN0XCI7XG4vKlxuICBHTE9CQUwgRlVOQ1RJT05TXG4qL1xuZnVuY3Rpb24gY2xpY2tPdXRzaWRlRWxlbWVudChlbGVtZW50LCBjYWxsYmFjaywgZXhjZXB0aW9uRWxlbWVudCA9IG51bGwpIHtcbiAgICBpZiAoIWVsZW1lbnQpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgIH1cbiAgICBjb25zdCBvdXRzaWRlQ2xpY2tMaXN0ZW5lciA9IChldmVudCkgPT4ge1xuICAgICAgICBjb25zdCB0YXJnZXQgPSBldmVudC50YXJnZXQ7IC8vIENhc3QgdG8gTm9kZSBmb3IgY29udGFpbnMoKVxuICAgICAgICBpZiAoZWxlbWVudCAmJiAhZWxlbWVudC5jb250YWlucyh0YXJnZXQpICYmICghZXhjZXB0aW9uRWxlbWVudCB8fCAhZXhjZXB0aW9uRWxlbWVudC5jb250YWlucyh0YXJnZXQpKSkge1xuICAgICAgICAgICAgY2FsbGJhY2soKTtcbiAgICAgICAgfVxuICAgIH07XG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBvdXRzaWRlQ2xpY2tMaXN0ZW5lcik7XG4gICAgcmV0dXJuICgpID0+IHtcbiAgICAgICAgZG9jdW1lbnQucmVtb3ZlRXZlbnRMaXN0ZW5lcignY2xpY2snLCBvdXRzaWRlQ2xpY2tMaXN0ZW5lcik7XG4gICAgfTtcbn1cbi8qKlxuICogSWYgcGFnZSBpcyBub3QgYXQgdG9wLCBhZGQgc2Nyb2xsaW5nIGNsYXNzIHRvIGJvZHlcbiAqIElmIHBhZ2UgaXMgc2Nyb2xsaW5nIHVwLCBhZGQgc2Nyb2xsaW5nLXVwIGNsYXNzIHRvIGJvZHlcbiAqL1xubGV0IGxhc3RTY3JvbGxUb3AgPSAwO1xud2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3Njcm9sbCcsICgpID0+IHtcbiAgICBjb25zdCBib2R5ID0gZG9jdW1lbnQuYm9keTtcbiAgICBjb25zdCBzY3JvbGxUb3AgPSB3aW5kb3cucGFnZVlPZmZzZXQgfHwgZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LnNjcm9sbFRvcDtcbiAgICBpZiAoc2Nyb2xsVG9wID4gMCkge1xuICAgICAgICBib2R5LmNsYXNzTGlzdC5hZGQoJ3Njcm9sbGluZycpO1xuICAgIH1cbiAgICBlbHNlIHtcbiAgICAgICAgYm9keS5jbGFzc0xpc3QucmVtb3ZlKCdzY3JvbGxpbmcnKTtcbiAgICB9XG4gICAgaWYgKHNjcm9sbFRvcCA+IGxhc3RTY3JvbGxUb3ApIHtcbiAgICAgICAgYm9keS5jbGFzc0xpc3QucmVtb3ZlKCdzY3JvbGxpbmctdXAnKTtcbiAgICB9XG4gICAgZWxzZSB7XG4gICAgICAgIGJvZHkuY2xhc3NMaXN0LmFkZCgnc2Nyb2xsaW5nLXVwJyk7XG4gICAgfVxuICAgIGxhc3RTY3JvbGxUb3AgPSBzY3JvbGxUb3A7XG59KTtcbi8qXG4gIFNFQVJDSCBGT1JNIEFOSU1BVElPTlxuKi9cbmNvbnN0IHNlYXJjaEZvcm0gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuc2VhcmNoLWZvcm0nKTtcbmNvbnN0IHNlYXJjaEZpZWxkID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnNlYXJjaC1maWVsZCcpO1xuLy8gQ2hlY2sgaWYgZWxlbWVudHMgZXhpc3RcbmlmIChzZWFyY2hGb3JtICYmIHNlYXJjaEZpZWxkKSB7XG4gICAgc2VhcmNoRmllbGQuYWRkRXZlbnRMaXN0ZW5lcignZm9jdXMnLCAoKSA9PiB7XG4gICAgICAgIHNlYXJjaEZvcm0uc3R5bGUud2lkdGggPSAnMjAwcHgnO1xuICAgIH0pO1xuICAgIHNlYXJjaEZpZWxkLmFkZEV2ZW50TGlzdGVuZXIoJ2JsdXInLCAoKSA9PiB7XG4gICAgICAgIHNlYXJjaEZvcm0uc3R5bGUucmVtb3ZlUHJvcGVydHkoXCJ3aWR0aFwiKTtcbiAgICB9KTtcbn1cbi8qXG4gIE1FTlUgU0VUVVBcbiovXG5jb25zdCBtZW51QnV0dG9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIiNtZW51LWJ1dHRvblwiKTtcbmNvbnN0IHByaW1hcnlNZW51ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIiNwcmltYXJ5LW1lbnVcIik7XG5jb25zdCBiYXJzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbChcIiNtZW51LWJ1dHRvbiAuYmFyXCIpO1xuY29uc3Qgc3ViTWVudXMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiI3ByaW1hcnktbWVudSAuc3ViLW1lbnVcIik7XG5jb25zdCBtZW51VG9nZ2xlcyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJyNwcmltYXJ5LW1lbnUgLm1lbnUtdG9nZ2xlJyk7XG5mdW5jdGlvbiBjbG9zZU1lbnUoKSB7XG4gICAgbWVudVRvZ2dsZXMuZm9yRWFjaChtZW51VG9nZ2xlID0+IG1lbnVUb2dnbGUuY2xhc3NMaXN0LnJlbW92ZShcIm9uXCIpKTtcbiAgICBzdWJNZW51cy5mb3JFYWNoKHN1Yk1lbnUgPT4gc3ViTWVudS5jbGFzc0xpc3QucmVtb3ZlKFwiYWN0aXZlXCIpKTtcbiAgICBpZiAocHJpbWFyeU1lbnUpIHtcbiAgICAgICAgcHJpbWFyeU1lbnUuY2xhc3NMaXN0LnJlbW92ZShcIm9wZW5cIik7XG4gICAgfVxuICAgIGlmIChtZW51QnV0dG9uKSB7XG4gICAgICAgIG1lbnVCdXR0b24uY2xhc3NMaXN0LnJlbW92ZShcImFjdGl2ZVwiKTtcbiAgICB9XG4gICAgLy8gRW5hYmxlIHNjcm9sbGluZ1xuICAgIGRvY3VtZW50LmJvZHkuc3R5bGUub3ZlcmZsb3dZID0gJ2F1dG8nO1xuICAgIC8vIFJlc2V0IG1lbnVCdXR0b24gc3R5bGVzXG4gICAgc2V0VGltZW91dCgoKSA9PiB7XG4gICAgICAgIGJhcnMuZm9yRWFjaChiYXIgPT4gYmFyLnJlbW92ZUF0dHJpYnV0ZShcInN0eWxlXCIpKTtcbiAgICB9LCAyMDApO1xufVxuLypcbiAgQU5JTUFUSU9OUyBGT1IgTUVOVSBCVVRUT05cbiovXG5tZW51VG9nZ2xlcy5mb3JFYWNoKG1lbnVUb2dnbGUgPT4gbWVudVRvZ2dsZS5jbGFzc0xpc3QucmVtb3ZlKFwib25cIikpO1xuaWYgKG1lbnVCdXR0b24gJiYgcHJpbWFyeU1lbnUpIHtcbiAgICBtZW51QnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoKSA9PiB7XG4gICAgICAgIGlmIChwcmltYXJ5TWVudS5jbGFzc0xpc3QuY29udGFpbnMoXCJvcGVuXCIpKSB7XG4gICAgICAgICAgICAvLyBDbG9zaW5nIGFuaW1hdGlvblxuICAgICAgICAgICAgY2xvc2VNZW51KCk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAvLyBPcGVuaW5nIGFuaW1hdGlvblxuICAgICAgICAgICAgcHJpbWFyeU1lbnUuY2xhc3NMaXN0LmFkZChcIm9wZW5cIik7XG4gICAgICAgICAgICBtZW51QnV0dG9uLmNsYXNzTGlzdC5hZGQoXCJhY3RpdmVcIik7XG4gICAgICAgICAgICAvLyBEaXNhYmxlIHNjcm9sbGluZ1xuICAgICAgICAgICAgZG9jdW1lbnQuYm9keS5zdHlsZS5vdmVyZmxvd1kgPSAnaGlkZGVuJztcbiAgICAgICAgfVxuICAgIH0pO1xufVxuaWYgKHByaW1hcnlNZW51KSB7XG4gICAgY29uc3QgcmVtb3ZlQ2xpY2tMaXN0ZW5lciA9IGNsaWNrT3V0c2lkZUVsZW1lbnQocHJpbWFyeU1lbnUsICgpID0+IHtcbiAgICAgICAgY29uc29sZS5sb2coJ0NsaWNrZWQgb3V0c2lkZSB0aGUgcHJpbWFyeSBtZW51IScpO1xuICAgICAgICBjbG9zZU1lbnUoKTtcbiAgICB9LCBtZW51QnV0dG9uKTsgLy8gUGFzcyB0aGUgbWVudUJ1dHRvbiBhcyB0aGUgdGhpcmQgYXJndW1lbnRcbn1cbmVsc2Uge1xuICAgIGNvbnNvbGUuZXJyb3IoXCJQcmltYXJ5IG1lbnUgZWxlbWVudCBub3QgZm91bmQuXCIpO1xufVxuLypcbiAgTUVOVSBFWFBBTlRJT04gT04gTU9CSUxFXG4qL1xuY29uc3QgbWVudUl0ZW1zID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLm1lbnUtaXRlbS1oYXMtY2hpbGRyZW4nKTtcbm1lbnVJdGVtcy5mb3JFYWNoKGl0ZW0gPT4ge1xuICAgIGNvbnN0IGJ1dHRvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3NwYW4nKTtcbiAgICBidXR0b24uY2xhc3NMaXN0LmFkZCgnbWVudS10b2dnbGUnKTtcbiAgICBjb25zdCBmaXJzdExpbmsgPSBpdGVtLnF1ZXJ5U2VsZWN0b3IoJ2EnKTtcbiAgICBpZiAoZmlyc3RMaW5rKSB7XG4gICAgICAgIGl0ZW0uaW5zZXJ0QmVmb3JlKGJ1dHRvbiwgZmlyc3RMaW5rKTtcbiAgICB9XG4gICAgZWxzZSB7XG4gICAgICAgIGl0ZW0uYXBwZW5kQ2hpbGQoYnV0dG9uKTtcbiAgICB9XG4gICAgYnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKGV2ZW50KSA9PiB7XG4gICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIGNvbnN0IGZpcnN0U3ViTWVudSA9IGl0ZW0ucXVlcnlTZWxlY3RvcignLnN1Yi1tZW51Jyk7XG4gICAgICAgIGlmIChmaXJzdFN1Yk1lbnUpIHtcbiAgICAgICAgICAgIGZpcnN0U3ViTWVudS5jbGFzc0xpc3QudG9nZ2xlKCdhY3RpdmUnKTtcbiAgICAgICAgICAgIGJ1dHRvbi5jbGFzc0xpc3QudG9nZ2xlKCdvbicpOyAvLyBUb2dnbGUgdGhlICdvbicgY2xhc3Mgb24gdGhlIGJ1dHRvblxuICAgICAgICAgICAgLy8gQ2xvc2Ugb3RoZXIgb3BlbiBzdWItbWVudXMgYW5kIHVwZGF0ZSBidXR0b24gc3RhdGVzXG4gICAgICAgICAgICBjb25zdCBwYXJlbnRTdWJNZW51ID0gaXRlbS5wYXJlbnRFbGVtZW50O1xuICAgICAgICAgICAgaWYgKHBhcmVudFN1Yk1lbnUpIHtcbiAgICAgICAgICAgICAgICBjb25zdCBzaWJsaW5nTWVudUl0ZW1zID0gQXJyYXkuZnJvbShwYXJlbnRTdWJNZW51LmNoaWxkcmVuKS5maWx0ZXIoY2hpbGQgPT4gY2hpbGQgIT09IGl0ZW0gJiYgY2hpbGQuY2xhc3NMaXN0LmNvbnRhaW5zKCdtZW51LWl0ZW0taGFzLWNoaWxkcmVuJykpO1xuICAgICAgICAgICAgICAgIHNpYmxpbmdNZW51SXRlbXMuZm9yRWFjaChzaWJsaW5nID0+IHtcbiAgICAgICAgICAgICAgICAgICAgY29uc3Qgc2libGluZ1N1Yk1lbnUgPSBzaWJsaW5nLnF1ZXJ5U2VsZWN0b3IoJy5zdWItbWVudScpO1xuICAgICAgICAgICAgICAgICAgICBjb25zdCBzaWJsaW5nQnV0dG9uID0gc2libGluZy5xdWVyeVNlbGVjdG9yKCcubWVudS10b2dnbGUnKTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKHNpYmxpbmdTdWJNZW51ICYmIHNpYmxpbmdCdXR0b24pIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHNpYmxpbmdTdWJNZW51LmNsYXNzTGlzdC5yZW1vdmUoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgc2libGluZ0J1dHRvbi5jbGFzc0xpc3QucmVtb3ZlKCdvbicpOyAvLyBSZW1vdmUgJ29uJyBjbGFzcyBmcm9tIHNpYmxpbmcgYnV0dG9uc1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9KTtcbn0pO1xuLypcbiAgRVFVQUwgSEVJR0hUUyBDRUxMU1xuKi9cbmZ1bmN0aW9uIGVxdWFsaXplQ2VsbEhlaWdodHMoKSB7XG4gICAgY29uc3QgY2VsbFJvd3MgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcuY2VsbC1yb3cnKTtcbiAgICBjZWxsUm93cy5mb3JFYWNoKHJvdyA9PiB7XG4gICAgICAgIGNvbnN0IGNlbGxzID0gcm93LnF1ZXJ5U2VsZWN0b3JBbGwoJy5jZWxsJyk7XG4gICAgICAgIGxldCBtYXhIZWlnaHQgPSAwO1xuICAgICAgICBjZWxscy5mb3JFYWNoKGNlbGwgPT4ge1xuICAgICAgICAgICAgY29uc3QgaHRtbENlbGwgPSBjZWxsOyAvLyBDYXN0IHRvIEhUTUxFbGVtZW50XG4gICAgICAgICAgICBodG1sQ2VsbC5zdHlsZS5oZWlnaHQgPSAnYXV0byc7XG4gICAgICAgICAgICBtYXhIZWlnaHQgPSBNYXRoLm1heChtYXhIZWlnaHQsIGh0bWxDZWxsLm9mZnNldEhlaWdodCk7XG4gICAgICAgIH0pO1xuICAgICAgICBjZWxscy5mb3JFYWNoKGNlbGwgPT4ge1xuICAgICAgICAgICAgY29uc3QgaHRtbENlbGwgPSBjZWxsOyAvLyBDYXN0IHRvIEhUTUxFbGVtZW50XG4gICAgICAgICAgICBodG1sQ2VsbC5zdHlsZS5oZWlnaHQgPSBgJHttYXhIZWlnaHR9cHhgO1xuICAgICAgICB9KTtcbiAgICB9KTtcbn1cbi8vIENhbGwgaW5pdGlhbGx5IG9uIHBhZ2UgbG9hZFxud2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCBlcXVhbGl6ZUNlbGxIZWlnaHRzKTtcbi8vIENhbGwgb24gd2luZG93IHJlc2l6ZVxubGV0IHJlc2l6ZVRpbWVvdXQgPSBudWxsO1xud2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3Jlc2l6ZScsICgpID0+IHtcbiAgICBpZiAocmVzaXplVGltZW91dCkge1xuICAgICAgICBjbGVhclRpbWVvdXQocmVzaXplVGltZW91dCk7XG4gICAgfVxuICAgIHJlc2l6ZVRpbWVvdXQgPSBzZXRUaW1lb3V0KGVxdWFsaXplQ2VsbEhlaWdodHMsIDIwMCk7XG59KTtcbi8qXG4gIENPTlRFTlQgSU4gVklFV1BPUlRcbiovXG5mdW5jdGlvbiBpc0Z1bGx5SW5WaWV3cG9ydFJvYnVzdChlbGVtZW50KSB7XG4gICAgY29uc3QgcmVjdCA9IGVsZW1lbnQuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XG4gICAgcmV0dXJuIChyZWN0LnRvcCA+PSAwICYmXG4gICAgICAgIHJlY3QubGVmdCA+PSAwICYmXG4gICAgICAgIHJlY3QuYm90dG9tIDw9ICh3aW5kb3cuaW5uZXJIZWlnaHQgfHwgZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNsaWVudEhlaWdodCkgJiZcbiAgICAgICAgcmVjdC5yaWdodCA8PSAod2luZG93LmlubmVyV2lkdGggfHwgZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNsaWVudFdpZHRoKSk7XG59XG5mdW5jdGlvbiBoYW5kbGVWaXNpYmlsaXR5Q2hlY2soKSB7XG4gICAgY29uc3QgZWxlbWVudHMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiLnZpc2libGVcIik7IC8vIEdldCBhbGwgZWxlbWVudHMgd2l0aCB0aGUgY2xhc3MgXCJ2aXNpYmxlXCJcbiAgICBlbGVtZW50cy5mb3JFYWNoKChlbGVtZW50KSA9PiB7XG4gICAgICAgIGlmIChlbGVtZW50IGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpIHsgLy8gVHlwZSBndWFyZCB0byBlbnN1cmUgaXQncyBhbiBIVE1MRWxlbWVudFxuICAgICAgICAgICAgaWYgKGlzRnVsbHlJblZpZXdwb3J0Um9idXN0KGVsZW1lbnQpKSB7XG4gICAgICAgICAgICAgICAgLy8gRG8gc29tZXRoaW5nLCBlLmcuLCBhZGQgYSBjbGFzcywgY2hhbmdlIHN0eWxlcywgZXRjLlxuICAgICAgICAgICAgICAgIGVsZW1lbnQuY2xhc3NMaXN0LmFkZChcImlzLXZpc2libGVcIik7IC8vIEV4YW1wbGU6IGFkZCBhIGNsYXNzXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBlbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJpcy12aXNpYmxlXCIpOyAvLyBFeGFtcGxlOiByZW1vdmUgdGhlIGNsYXNzXG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9KTtcbn1cbi8vIEluaXRpYWwgY2hlY2sgd2hlbiB0aGUgcGFnZSBsb2FkczpcbmhhbmRsZVZpc2liaWxpdHlDaGVjaygpO1xuLy8gQ2hlY2sgb24gc2Nyb2xsOlxud2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3Njcm9sbCcsIGhhbmRsZVZpc2liaWxpdHlDaGVjayk7XG4vLyBDaGVjayBvbiByZXNpemUgKGltcG9ydGFudCBmb3Igdmlld3BvcnQgY2hhbmdlcyk6XG53aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcigncmVzaXplJywgaGFuZGxlVmlzaWJpbGl0eUNoZWNrKTtcbi8vIEV4YW1wbGUgdXNpbmcgSW50ZXJzZWN0aW9uIE9ic2VydmVyIChNb3JlIHBlcmZvcm1hbnQgZm9yIGZyZXF1ZW50IGNoYW5nZXMpOlxuY29uc3Qgb2JzZXJ2ZXIgPSBuZXcgSW50ZXJzZWN0aW9uT2JzZXJ2ZXIoKGVudHJpZXMpID0+IHtcbiAgICBlbnRyaWVzLmZvckVhY2goKGVudHJ5KSA9PiB7XG4gICAgICAgIGNvbnN0IGVsZW1lbnQgPSBlbnRyeS50YXJnZXQ7IC8vIFR5cGUgYXNzZXJ0aW9uXG4gICAgICAgIGlmIChlbnRyeS5pc0ludGVyc2VjdGluZyAmJiBlbnRyeS5pbnRlcnNlY3Rpb25SYXRpbyA9PT0gMSkge1xuICAgICAgICAgICAgZWxlbWVudC5jbGFzc0xpc3QuYWRkKFwiaXMtdmlzaWJsZVwiKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIGVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcImlzLXZpc2libGVcIik7XG4gICAgICAgIH1cbiAgICB9KTtcbn0sIHsgdGhyZXNob2xkOiAxIH0pO1xuY29uc3QgdmlzaWJsZUVsZW1lbnRzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbChcIi52aXNpYmxlXCIpO1xudmlzaWJsZUVsZW1lbnRzLmZvckVhY2goZWxlbWVudCA9PiB7XG4gICAgaWYgKGVsZW1lbnQgaW5zdGFuY2VvZiBIVE1MRWxlbWVudCkgeyAvLyBUeXBlIGd1YXJkXG4gICAgICAgIG9ic2VydmVyLm9ic2VydmUoZWxlbWVudCk7XG4gICAgfVxufSk7XG4vKipcbiAqIE9uIGNsaWNrIG9mICNsZWFndWUtZmlsdGVycyAubGVhZ3VlLWZpbHRlclxuICogZ2V0IHZhbHVlIGZyb20gZGF0YS1sZWFndWUtaWQgYXR0cmlidXRlXG4gKiBGaW5kICNsZWFndWUtY29udGFpbmVyIC5sZWFndWUgd2l0aCBtYXRjaGluZyBkYXRhLWxlYWd1ZS1pZFxuICogSGlkZSBhbGwgb3RoZXIgLmxlYWd1ZSBlbGVtZW50c1xuICogU2hvdyB0aGUgbWF0Y2hpbmcgLmxlYWd1ZSBlbGVtZW50XG4gKi9cbmNvbnN0IGxlYWd1ZUZpbHRlcnMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcjbGVhZ3VlLWZpbHRlcnMgLmxlYWd1ZS1maWx0ZXInKTtcbmNvbnN0IGxlYWd1ZUNvbnRhaW5lcnMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcjbGVhZ3Vlcy1zZWN0aW9uIC5sZWFndWUnKTtcbi8vIGNsaWNrIGV2ZW50XG5sZWFndWVGaWx0ZXJzLmZvckVhY2goZmlsdGVyID0+IHtcbiAgICBmaWx0ZXIuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoKSA9PiB7XG4gICAgICAgIC8vIGdpdmUgdGhlIGNsaWNrZWQgZmlsdGVyIHRoZSBhY3RpdmUgY2xhc3NcbiAgICAgICAgbGVhZ3VlRmlsdGVycy5mb3JFYWNoKGZpbHRlciA9PiBmaWx0ZXIuY2xhc3NMaXN0LnJlbW92ZSgnYWN0aXZlJykpO1xuICAgICAgICBmaWx0ZXIuY2xhc3NMaXN0LmFkZCgnYWN0aXZlJyk7XG4gICAgICAgIC8vIGdldCB0aGUgbGVhZ3VlSWQgZnJvbSB0aGUgY2xpY2tlZCBmaWx0ZXJcbiAgICAgICAgY29uc3QgbGVhZ3VlSWQgPSBmaWx0ZXIuZ2V0QXR0cmlidXRlKCdkYXRhLWxlYWd1ZS1pZCcpO1xuICAgICAgICBpZiAobGVhZ3VlSWQpIHtcbiAgICAgICAgICAgIC8vIGlmIGxlYWd1ZUlkIGlzIGVxdWFsIHRvIFwiYWxsXCIsIHNob3cgYWxsIGxlYWd1ZXNcbiAgICAgICAgICAgIGlmIChsZWFndWVJZCA9PT0gJ2FsbCcpIHtcbiAgICAgICAgICAgICAgICBsZWFndWVDb250YWluZXJzLmZvckVhY2gobGVhZ3VlID0+IHtcbiAgICAgICAgICAgICAgICAgICAgY29uc3QgbGVhZ3VlRWxlbWVudCA9IGxlYWd1ZTtcbiAgICAgICAgICAgICAgICAgICAgbGVhZ3VlRWxlbWVudC5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIC8vIGVsc2UsIHNob3cgb25seSB0aGUgbGVhZ3VlIHdpdGggdGhlIG1hdGNoaW5nIGxlYWd1ZUlkXG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBsZWFndWVDb250YWluZXJzLmZvckVhY2gobGVhZ3VlID0+IHtcbiAgICAgICAgICAgICAgICAgICAgY29uc3QgbGVhZ3VlRWxlbWVudCA9IGxlYWd1ZTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKGxlYWd1ZUVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLWxlYWd1ZS1pZCcpID09PSBsZWFndWVJZCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbGVhZ3VlRWxlbWVudC5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGxlYWd1ZUVsZW1lbnQuc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSk7XG59KTtcbi8qKlxuICogUHJvZ3JhbSBzZWFyY2ggZmlsdGVyc1xuICovXG5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiRE9NQ29udGVudExvYWRlZFwiLCAoKSA9PiB7XG4gICAgY29uc3QgZmlsdGVycyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCIjZmlsdGVycyBzZWxlY3RcIik7XG4gICAgYXBwbHlGaWx0ZXJzKCk7XG4gICAgZmlsdGVycy5mb3JFYWNoKGZpbHRlciA9PiB7XG4gICAgICAgIGZpbHRlci5hZGRFdmVudExpc3RlbmVyKFwiY2hhbmdlXCIsIGFwcGx5RmlsdGVycyk7XG4gICAgfSk7XG4gICAgZnVuY3Rpb24gYXBwbHlGaWx0ZXJzKCkge1xuICAgICAgICBjb25zdCBnZXRGaWx0ZXJWYWx1ZSA9IChpZCkgPT4ge1xuICAgICAgICAgICAgY29uc3QgZWxlbWVudCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGlkKTtcbiAgICAgICAgICAgIHJldHVybiBlbGVtZW50ID8gZWxlbWVudC52YWx1ZSA6IFwiXCI7XG4gICAgICAgIH07XG4gICAgICAgIGNvbnN0IHNlbGVjdGVkRmlsdGVycyA9IHtcbiAgICAgICAgICAgIHN0YXRlOiBnZXRGaWx0ZXJWYWx1ZShcInN0YXRlXCIpLFxuICAgICAgICAgICAgY2l0eTogZ2V0RmlsdGVyVmFsdWUoXCJjaXR5XCIpLFxuICAgICAgICAgICAgcHJvZ3JhbXM6IGdldEZpbHRlclZhbHVlKFwicHJvZ3JhbXNcIiksXG4gICAgICAgICAgICBzZWFzb246IGdldEZpbHRlclZhbHVlKFwic2Vhc29uXCIpLFxuICAgICAgICAgICAgYWdlOiBnZXRGaWx0ZXJWYWx1ZShcImFnZVwiKSxcbiAgICAgICAgICAgIGdlbmRlcjogZ2V0RmlsdGVyVmFsdWUoXCJnZW5kZXJcIilcbiAgICAgICAgfTtcbiAgICAgICAgY29uc3QgcHJvZ3JhbXMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiLnByb2dyYW1cIik7XG4gICAgICAgIHByb2dyYW1zLmZvckVhY2gocHJvZ3JhbSA9PiB7XG4gICAgICAgICAgICB2YXIgX2EsIF9iLCBfYywgX2QsIF9lLCBfZjtcbiAgICAgICAgICAgIGNvbnN0IHByb2dyYW1FbGVtZW50ID0gcHJvZ3JhbTtcbiAgICAgICAgICAgIGNvbnN0IHN0YXRlID0gKChfYSA9IHByb2dyYW1FbGVtZW50LmRhdGFzZXQuc3RhdGUpID09PSBudWxsIHx8IF9hID09PSB2b2lkIDAgPyB2b2lkIDAgOiBfYS50cmltKCkpIHx8IFwiXCI7XG4gICAgICAgICAgICBjb25zdCBjaXR5ID0gKChfYiA9IHByb2dyYW1FbGVtZW50LmRhdGFzZXQuY2l0eSkgPT09IG51bGwgfHwgX2IgPT09IHZvaWQgMCA/IHZvaWQgMCA6IF9iLnRyaW0oKSkgfHwgXCJcIjtcbiAgICAgICAgICAgIGNvbnN0IHByb2dyYW1zID0gKChfYyA9IHByb2dyYW1FbGVtZW50LmRhdGFzZXQucHJvZ3JhbXMpID09PSBudWxsIHx8IF9jID09PSB2b2lkIDAgPyB2b2lkIDAgOiBfYy50cmltKCkpIHx8IFwiXCI7XG4gICAgICAgICAgICBjb25zdCBzZWFzb24gPSAoKF9kID0gcHJvZ3JhbUVsZW1lbnQuZGF0YXNldC5zZWFzb24pID09PSBudWxsIHx8IF9kID09PSB2b2lkIDAgPyB2b2lkIDAgOiBfZC50cmltKCkpIHx8IFwiXCI7XG4gICAgICAgICAgICBjb25zdCBhZ2VzID0gKChfZSA9IHByb2dyYW1FbGVtZW50LmRhdGFzZXQuYWdlcykgPT09IG51bGwgfHwgX2UgPT09IHZvaWQgMCA/IHZvaWQgMCA6IF9lLnNwbGl0KFwiLFwiKS5tYXAoYWdlID0+IGFnZS50cmltKCkpKSB8fCBbXTtcbiAgICAgICAgICAgIGNvbnN0IGdlbmRlciA9ICgoX2YgPSBwcm9ncmFtRWxlbWVudC5kYXRhc2V0LmdlbmRlcikgPT09IG51bGwgfHwgX2YgPT09IHZvaWQgMCA/IHZvaWQgMCA6IF9mLnRyaW0oKSkgfHwgXCJcIjtcbiAgICAgICAgICAgIGxldCBtYXRjaGVzID0gdHJ1ZTtcbiAgICAgICAgICAgIGlmIChzZWxlY3RlZEZpbHRlcnMuc3RhdGUgJiYgc3RhdGUgIT09IHNlbGVjdGVkRmlsdGVycy5zdGF0ZSlcbiAgICAgICAgICAgICAgICBtYXRjaGVzID0gZmFsc2U7XG4gICAgICAgICAgICBpZiAoc2VsZWN0ZWRGaWx0ZXJzLmNpdHkgJiYgY2l0eSAhPT0gc2VsZWN0ZWRGaWx0ZXJzLmNpdHkpXG4gICAgICAgICAgICAgICAgbWF0Y2hlcyA9IGZhbHNlO1xuICAgICAgICAgICAgaWYgKHNlbGVjdGVkRmlsdGVycy5wcm9ncmFtcyAmJiBwcm9ncmFtcyAhPT0gc2VsZWN0ZWRGaWx0ZXJzLnByb2dyYW1zKVxuICAgICAgICAgICAgICAgIG1hdGNoZXMgPSBmYWxzZTtcbiAgICAgICAgICAgIGlmIChzZWxlY3RlZEZpbHRlcnMuc2Vhc29uICYmIHNlYXNvbiAhPT0gc2VsZWN0ZWRGaWx0ZXJzLnNlYXNvbilcbiAgICAgICAgICAgICAgICBtYXRjaGVzID0gZmFsc2U7XG4gICAgICAgICAgICBpZiAoc2VsZWN0ZWRGaWx0ZXJzLmFnZSAmJiAhYWdlcy5pbmNsdWRlcyhzZWxlY3RlZEZpbHRlcnMuYWdlKSlcbiAgICAgICAgICAgICAgICBtYXRjaGVzID0gZmFsc2U7XG4gICAgICAgICAgICBpZiAoc2VsZWN0ZWRGaWx0ZXJzLmdlbmRlciAmJiBnZW5kZXIgIT09IHNlbGVjdGVkRmlsdGVycy5nZW5kZXIpXG4gICAgICAgICAgICAgICAgbWF0Y2hlcyA9IGZhbHNlO1xuICAgICAgICAgICAgcHJvZ3JhbUVsZW1lbnQuc3R5bGUuZGlzcGxheSA9IG1hdGNoZXMgPyBcImJsb2NrXCIgOiBcIm5vbmVcIjtcbiAgICAgICAgfSk7XG4gICAgfVxufSk7XG4iXSwibmFtZXMiOltdLCJzb3VyY2VSb290IjoiIn0=