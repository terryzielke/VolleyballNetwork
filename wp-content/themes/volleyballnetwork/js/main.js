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
/**
 * Adjust width of select element based on selected option
 */
function adjustSelectWidth(select) {
    const selectedOption = select.options[select.selectedIndex];
    const tempSpan = document.createElement('span');
    tempSpan.style.font = window.getComputedStyle(select).font;
    tempSpan.style.visibility = 'hidden';
    tempSpan.style.position = 'absolute';
    tempSpan.textContent = selectedOption.textContent;
    document.body.appendChild(tempSpan);
    select.style.width = tempSpan.offsetWidth * 1.2 + 'px';
    document.body.removeChild(tempSpan);
}
document.addEventListener('DOMContentLoaded', () => {
    const selectElement = document.querySelector('#find-program-select');
    const findProgramButton = document.querySelector('#find-program-button');
    if (selectElement && findProgramButton) {
        selectElement.addEventListener('change', () => {
            adjustSelectWidth(selectElement);
            const selectedValue = selectElement.value.replace(/\s/g, '-').toLowerCase();
            const baseUrl = '/program-search';
            if (selectedValue) {
                findProgramButton.href = `${baseUrl}/?state=${selectedValue}`;
            }
            else {
                findProgramButton.href = baseUrl; // Reset if no value selected
            }
        });
        adjustSelectWidth(selectElement); // Initial width set.
    }
});

/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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QUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLENBQUM7QUFDRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsNENBQTRDLFFBQVEsVUFBVSxjQUFjO0FBQzVFO0FBQ0E7QUFDQSxrREFBa0Q7QUFDbEQ7QUFDQSxTQUFTO0FBQ1QsMENBQTBDO0FBQzFDO0FBQ0EsQ0FBQyIsInNvdXJjZXMiOlsid2VicGFjazovL2NvbXBsZXRlLXNhc3MtdHV0b3JpYWwvLi9zcmMvbWFpbi50cyJdLCJzb3VyY2VzQ29udGVudCI6WyJcInVzZSBzdHJpY3RcIjtcbi8qXG4gIEdMT0JBTCBGVU5DVElPTlNcbiovXG5mdW5jdGlvbiBjbGlja091dHNpZGVFbGVtZW50KGVsZW1lbnQsIGNhbGxiYWNrLCBleGNlcHRpb25FbGVtZW50ID0gbnVsbCkge1xuICAgIGlmICghZWxlbWVudCkge1xuICAgICAgICByZXR1cm47XG4gICAgfVxuICAgIGNvbnN0IG91dHNpZGVDbGlja0xpc3RlbmVyID0gKGV2ZW50KSA9PiB7XG4gICAgICAgIGNvbnN0IHRhcmdldCA9IGV2ZW50LnRhcmdldDsgLy8gQ2FzdCB0byBOb2RlIGZvciBjb250YWlucygpXG4gICAgICAgIGlmIChlbGVtZW50ICYmICFlbGVtZW50LmNvbnRhaW5zKHRhcmdldCkgJiYgKCFleGNlcHRpb25FbGVtZW50IHx8ICFleGNlcHRpb25FbGVtZW50LmNvbnRhaW5zKHRhcmdldCkpKSB7XG4gICAgICAgICAgICBjYWxsYmFjaygpO1xuICAgICAgICB9XG4gICAgfTtcbiAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIG91dHNpZGVDbGlja0xpc3RlbmVyKTtcbiAgICByZXR1cm4gKCkgPT4ge1xuICAgICAgICBkb2N1bWVudC5yZW1vdmVFdmVudExpc3RlbmVyKCdjbGljaycsIG91dHNpZGVDbGlja0xpc3RlbmVyKTtcbiAgICB9O1xufVxuLyoqXG4gKiBJZiBwYWdlIGlzIG5vdCBhdCB0b3AsIGFkZCBzY3JvbGxpbmcgY2xhc3MgdG8gYm9keVxuICogSWYgcGFnZSBpcyBzY3JvbGxpbmcgdXAsIGFkZCBzY3JvbGxpbmctdXAgY2xhc3MgdG8gYm9keVxuICovXG5sZXQgbGFzdFNjcm9sbFRvcCA9IDA7XG53aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignc2Nyb2xsJywgKCkgPT4ge1xuICAgIGNvbnN0IGJvZHkgPSBkb2N1bWVudC5ib2R5O1xuICAgIGNvbnN0IHNjcm9sbFRvcCA9IHdpbmRvdy5wYWdlWU9mZnNldCB8fCBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuc2Nyb2xsVG9wO1xuICAgIGlmIChzY3JvbGxUb3AgPiAwKSB7XG4gICAgICAgIGJvZHkuY2xhc3NMaXN0LmFkZCgnc2Nyb2xsaW5nJyk7XG4gICAgfVxuICAgIGVsc2Uge1xuICAgICAgICBib2R5LmNsYXNzTGlzdC5yZW1vdmUoJ3Njcm9sbGluZycpO1xuICAgIH1cbiAgICBpZiAoc2Nyb2xsVG9wID4gbGFzdFNjcm9sbFRvcCkge1xuICAgICAgICBib2R5LmNsYXNzTGlzdC5yZW1vdmUoJ3Njcm9sbGluZy11cCcpO1xuICAgIH1cbiAgICBlbHNlIHtcbiAgICAgICAgYm9keS5jbGFzc0xpc3QuYWRkKCdzY3JvbGxpbmctdXAnKTtcbiAgICB9XG4gICAgbGFzdFNjcm9sbFRvcCA9IHNjcm9sbFRvcDtcbn0pO1xuLypcbiAgU0VBUkNIIEZPUk0gQU5JTUFUSU9OXG4qL1xuY29uc3Qgc2VhcmNoRm9ybSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5zZWFyY2gtZm9ybScpO1xuY29uc3Qgc2VhcmNoRmllbGQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuc2VhcmNoLWZpZWxkJyk7XG4vLyBDaGVjayBpZiBlbGVtZW50cyBleGlzdFxuaWYgKHNlYXJjaEZvcm0gJiYgc2VhcmNoRmllbGQpIHtcbiAgICBzZWFyY2hGaWVsZC5hZGRFdmVudExpc3RlbmVyKCdmb2N1cycsICgpID0+IHtcbiAgICAgICAgc2VhcmNoRm9ybS5zdHlsZS53aWR0aCA9ICcyMDBweCc7XG4gICAgfSk7XG4gICAgc2VhcmNoRmllbGQuYWRkRXZlbnRMaXN0ZW5lcignYmx1cicsICgpID0+IHtcbiAgICAgICAgc2VhcmNoRm9ybS5zdHlsZS5yZW1vdmVQcm9wZXJ0eShcIndpZHRoXCIpO1xuICAgIH0pO1xufVxuLypcbiAgTUVOVSBTRVRVUFxuKi9cbmNvbnN0IG1lbnVCdXR0b24gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiI21lbnUtYnV0dG9uXCIpO1xuY29uc3QgcHJpbWFyeU1lbnUgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiI3ByaW1hcnktbWVudVwiKTtcbmNvbnN0IGJhcnMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiI21lbnUtYnV0dG9uIC5iYXJcIik7XG5jb25zdCBzdWJNZW51cyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCIjcHJpbWFyeS1tZW51IC5zdWItbWVudVwiKTtcbmNvbnN0IG1lbnVUb2dnbGVzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnI3ByaW1hcnktbWVudSAubWVudS10b2dnbGUnKTtcbmZ1bmN0aW9uIGNsb3NlTWVudSgpIHtcbiAgICBtZW51VG9nZ2xlcy5mb3JFYWNoKG1lbnVUb2dnbGUgPT4gbWVudVRvZ2dsZS5jbGFzc0xpc3QucmVtb3ZlKFwib25cIikpO1xuICAgIHN1Yk1lbnVzLmZvckVhY2goc3ViTWVudSA9PiBzdWJNZW51LmNsYXNzTGlzdC5yZW1vdmUoXCJhY3RpdmVcIikpO1xuICAgIGlmIChwcmltYXJ5TWVudSkge1xuICAgICAgICBwcmltYXJ5TWVudS5jbGFzc0xpc3QucmVtb3ZlKFwib3BlblwiKTtcbiAgICB9XG4gICAgaWYgKG1lbnVCdXR0b24pIHtcbiAgICAgICAgbWVudUJ1dHRvbi5jbGFzc0xpc3QucmVtb3ZlKFwiYWN0aXZlXCIpO1xuICAgIH1cbiAgICAvLyBFbmFibGUgc2Nyb2xsaW5nXG4gICAgZG9jdW1lbnQuYm9keS5zdHlsZS5vdmVyZmxvd1kgPSAnYXV0byc7XG4gICAgLy8gUmVzZXQgbWVudUJ1dHRvbiBzdHlsZXNcbiAgICBzZXRUaW1lb3V0KCgpID0+IHtcbiAgICAgICAgYmFycy5mb3JFYWNoKGJhciA9PiBiYXIucmVtb3ZlQXR0cmlidXRlKFwic3R5bGVcIikpO1xuICAgIH0sIDIwMCk7XG59XG4vKlxuICBBTklNQVRJT05TIEZPUiBNRU5VIEJVVFRPTlxuKi9cbm1lbnVUb2dnbGVzLmZvckVhY2gobWVudVRvZ2dsZSA9PiBtZW51VG9nZ2xlLmNsYXNzTGlzdC5yZW1vdmUoXCJvblwiKSk7XG5pZiAobWVudUJ1dHRvbiAmJiBwcmltYXJ5TWVudSkge1xuICAgIG1lbnVCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsICgpID0+IHtcbiAgICAgICAgaWYgKHByaW1hcnlNZW51LmNsYXNzTGlzdC5jb250YWlucyhcIm9wZW5cIikpIHtcbiAgICAgICAgICAgIC8vIENsb3NpbmcgYW5pbWF0aW9uXG4gICAgICAgICAgICBjbG9zZU1lbnUoKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIC8vIE9wZW5pbmcgYW5pbWF0aW9uXG4gICAgICAgICAgICBwcmltYXJ5TWVudS5jbGFzc0xpc3QuYWRkKFwib3BlblwiKTtcbiAgICAgICAgICAgIG1lbnVCdXR0b24uY2xhc3NMaXN0LmFkZChcImFjdGl2ZVwiKTtcbiAgICAgICAgICAgIC8vIERpc2FibGUgc2Nyb2xsaW5nXG4gICAgICAgICAgICBkb2N1bWVudC5ib2R5LnN0eWxlLm92ZXJmbG93WSA9ICdoaWRkZW4nO1xuICAgICAgICB9XG4gICAgfSk7XG59XG5pZiAocHJpbWFyeU1lbnUpIHtcbiAgICBjb25zdCByZW1vdmVDbGlja0xpc3RlbmVyID0gY2xpY2tPdXRzaWRlRWxlbWVudChwcmltYXJ5TWVudSwgKCkgPT4ge1xuICAgICAgICBjb25zb2xlLmxvZygnQ2xpY2tlZCBvdXRzaWRlIHRoZSBwcmltYXJ5IG1lbnUhJyk7XG4gICAgICAgIGNsb3NlTWVudSgpO1xuICAgIH0sIG1lbnVCdXR0b24pOyAvLyBQYXNzIHRoZSBtZW51QnV0dG9uIGFzIHRoZSB0aGlyZCBhcmd1bWVudFxufVxuZWxzZSB7XG4gICAgY29uc29sZS5lcnJvcihcIlByaW1hcnkgbWVudSBlbGVtZW50IG5vdCBmb3VuZC5cIik7XG59XG4vKlxuICBNRU5VIEVYUEFOVElPTiBPTiBNT0JJTEVcbiovXG5jb25zdCBtZW51SXRlbXMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcubWVudS1pdGVtLWhhcy1jaGlsZHJlbicpO1xubWVudUl0ZW1zLmZvckVhY2goaXRlbSA9PiB7XG4gICAgY29uc3QgYnV0dG9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc3BhbicpO1xuICAgIGJ1dHRvbi5jbGFzc0xpc3QuYWRkKCdtZW51LXRvZ2dsZScpO1xuICAgIGNvbnN0IGZpcnN0TGluayA9IGl0ZW0ucXVlcnlTZWxlY3RvcignYScpO1xuICAgIGlmIChmaXJzdExpbmspIHtcbiAgICAgICAgaXRlbS5pbnNlcnRCZWZvcmUoYnV0dG9uLCBmaXJzdExpbmspO1xuICAgIH1cbiAgICBlbHNlIHtcbiAgICAgICAgaXRlbS5hcHBlbmRDaGlsZChidXR0b24pO1xuICAgIH1cbiAgICBidXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoZXZlbnQpID0+IHtcbiAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgY29uc3QgZmlyc3RTdWJNZW51ID0gaXRlbS5xdWVyeVNlbGVjdG9yKCcuc3ViLW1lbnUnKTtcbiAgICAgICAgaWYgKGZpcnN0U3ViTWVudSkge1xuICAgICAgICAgICAgZmlyc3RTdWJNZW51LmNsYXNzTGlzdC50b2dnbGUoJ2FjdGl2ZScpO1xuICAgICAgICAgICAgYnV0dG9uLmNsYXNzTGlzdC50b2dnbGUoJ29uJyk7IC8vIFRvZ2dsZSB0aGUgJ29uJyBjbGFzcyBvbiB0aGUgYnV0dG9uXG4gICAgICAgICAgICAvLyBDbG9zZSBvdGhlciBvcGVuIHN1Yi1tZW51cyBhbmQgdXBkYXRlIGJ1dHRvbiBzdGF0ZXNcbiAgICAgICAgICAgIGNvbnN0IHBhcmVudFN1Yk1lbnUgPSBpdGVtLnBhcmVudEVsZW1lbnQ7XG4gICAgICAgICAgICBpZiAocGFyZW50U3ViTWVudSkge1xuICAgICAgICAgICAgICAgIGNvbnN0IHNpYmxpbmdNZW51SXRlbXMgPSBBcnJheS5mcm9tKHBhcmVudFN1Yk1lbnUuY2hpbGRyZW4pLmZpbHRlcihjaGlsZCA9PiBjaGlsZCAhPT0gaXRlbSAmJiBjaGlsZC5jbGFzc0xpc3QuY29udGFpbnMoJ21lbnUtaXRlbS1oYXMtY2hpbGRyZW4nKSk7XG4gICAgICAgICAgICAgICAgc2libGluZ01lbnVJdGVtcy5mb3JFYWNoKHNpYmxpbmcgPT4ge1xuICAgICAgICAgICAgICAgICAgICBjb25zdCBzaWJsaW5nU3ViTWVudSA9IHNpYmxpbmcucXVlcnlTZWxlY3RvcignLnN1Yi1tZW51Jyk7XG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IHNpYmxpbmdCdXR0b24gPSBzaWJsaW5nLnF1ZXJ5U2VsZWN0b3IoJy5tZW51LXRvZ2dsZScpO1xuICAgICAgICAgICAgICAgICAgICBpZiAoc2libGluZ1N1Yk1lbnUgJiYgc2libGluZ0J1dHRvbikge1xuICAgICAgICAgICAgICAgICAgICAgICAgc2libGluZ1N1Yk1lbnUuY2xhc3NMaXN0LnJlbW92ZSgnYWN0aXZlJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBzaWJsaW5nQnV0dG9uLmNsYXNzTGlzdC5yZW1vdmUoJ29uJyk7IC8vIFJlbW92ZSAnb24nIGNsYXNzIGZyb20gc2libGluZyBidXR0b25zXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0pO1xufSk7XG4vKlxuICBFUVVBTCBIRUlHSFRTIENFTExTXG4qL1xuZnVuY3Rpb24gZXF1YWxpemVDZWxsSGVpZ2h0cygpIHtcbiAgICBjb25zdCBjZWxsUm93cyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5jZWxsLXJvdycpO1xuICAgIGNlbGxSb3dzLmZvckVhY2gocm93ID0+IHtcbiAgICAgICAgY29uc3QgY2VsbHMgPSByb3cucXVlcnlTZWxlY3RvckFsbCgnLmNlbGwnKTtcbiAgICAgICAgbGV0IG1heEhlaWdodCA9IDA7XG4gICAgICAgIGNlbGxzLmZvckVhY2goY2VsbCA9PiB7XG4gICAgICAgICAgICBjb25zdCBodG1sQ2VsbCA9IGNlbGw7IC8vIENhc3QgdG8gSFRNTEVsZW1lbnRcbiAgICAgICAgICAgIGh0bWxDZWxsLnN0eWxlLmhlaWdodCA9ICdhdXRvJztcbiAgICAgICAgICAgIG1heEhlaWdodCA9IE1hdGgubWF4KG1heEhlaWdodCwgaHRtbENlbGwub2Zmc2V0SGVpZ2h0KTtcbiAgICAgICAgfSk7XG4gICAgICAgIGNlbGxzLmZvckVhY2goY2VsbCA9PiB7XG4gICAgICAgICAgICBjb25zdCBodG1sQ2VsbCA9IGNlbGw7IC8vIENhc3QgdG8gSFRNTEVsZW1lbnRcbiAgICAgICAgICAgIGh0bWxDZWxsLnN0eWxlLmhlaWdodCA9IGAke21heEhlaWdodH1weGA7XG4gICAgICAgIH0pO1xuICAgIH0pO1xufVxuLy8gQ2FsbCBpbml0aWFsbHkgb24gcGFnZSBsb2FkXG53aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsIGVxdWFsaXplQ2VsbEhlaWdodHMpO1xuLy8gQ2FsbCBvbiB3aW5kb3cgcmVzaXplXG5sZXQgcmVzaXplVGltZW91dCA9IG51bGw7XG53aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcigncmVzaXplJywgKCkgPT4ge1xuICAgIGlmIChyZXNpemVUaW1lb3V0KSB7XG4gICAgICAgIGNsZWFyVGltZW91dChyZXNpemVUaW1lb3V0KTtcbiAgICB9XG4gICAgcmVzaXplVGltZW91dCA9IHNldFRpbWVvdXQoZXF1YWxpemVDZWxsSGVpZ2h0cywgMjAwKTtcbn0pO1xuLypcbiAgQ09OVEVOVCBJTiBWSUVXUE9SVFxuKi9cbmZ1bmN0aW9uIGlzRnVsbHlJblZpZXdwb3J0Um9idXN0KGVsZW1lbnQpIHtcbiAgICBjb25zdCByZWN0ID0gZWxlbWVudC5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKTtcbiAgICByZXR1cm4gKHJlY3QudG9wID49IDAgJiZcbiAgICAgICAgcmVjdC5sZWZ0ID49IDAgJiZcbiAgICAgICAgcmVjdC5ib3R0b20gPD0gKHdpbmRvdy5pbm5lckhlaWdodCB8fCBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY2xpZW50SGVpZ2h0KSAmJlxuICAgICAgICByZWN0LnJpZ2h0IDw9ICh3aW5kb3cuaW5uZXJXaWR0aCB8fCBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY2xpZW50V2lkdGgpKTtcbn1cbmZ1bmN0aW9uIGhhbmRsZVZpc2liaWxpdHlDaGVjaygpIHtcbiAgICBjb25zdCBlbGVtZW50cyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCIudmlzaWJsZVwiKTsgLy8gR2V0IGFsbCBlbGVtZW50cyB3aXRoIHRoZSBjbGFzcyBcInZpc2libGVcIlxuICAgIGVsZW1lbnRzLmZvckVhY2goKGVsZW1lbnQpID0+IHtcbiAgICAgICAgaWYgKGVsZW1lbnQgaW5zdGFuY2VvZiBIVE1MRWxlbWVudCkgeyAvLyBUeXBlIGd1YXJkIHRvIGVuc3VyZSBpdCdzIGFuIEhUTUxFbGVtZW50XG4gICAgICAgICAgICBpZiAoaXNGdWxseUluVmlld3BvcnRSb2J1c3QoZWxlbWVudCkpIHtcbiAgICAgICAgICAgICAgICAvLyBEbyBzb21ldGhpbmcsIGUuZy4sIGFkZCBhIGNsYXNzLCBjaGFuZ2Ugc3R5bGVzLCBldGMuXG4gICAgICAgICAgICAgICAgZWxlbWVudC5jbGFzc0xpc3QuYWRkKFwiaXMtdmlzaWJsZVwiKTsgLy8gRXhhbXBsZTogYWRkIGEgY2xhc3NcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIGVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcImlzLXZpc2libGVcIik7IC8vIEV4YW1wbGU6IHJlbW92ZSB0aGUgY2xhc3NcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0pO1xufVxuLy8gSW5pdGlhbCBjaGVjayB3aGVuIHRoZSBwYWdlIGxvYWRzOlxuaGFuZGxlVmlzaWJpbGl0eUNoZWNrKCk7XG4vLyBDaGVjayBvbiBzY3JvbGw6XG53aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignc2Nyb2xsJywgaGFuZGxlVmlzaWJpbGl0eUNoZWNrKTtcbi8vIENoZWNrIG9uIHJlc2l6ZSAoaW1wb3J0YW50IGZvciB2aWV3cG9ydCBjaGFuZ2VzKTpcbndpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdyZXNpemUnLCBoYW5kbGVWaXNpYmlsaXR5Q2hlY2spO1xuLy8gRXhhbXBsZSB1c2luZyBJbnRlcnNlY3Rpb24gT2JzZXJ2ZXIgKE1vcmUgcGVyZm9ybWFudCBmb3IgZnJlcXVlbnQgY2hhbmdlcyk6XG5jb25zdCBvYnNlcnZlciA9IG5ldyBJbnRlcnNlY3Rpb25PYnNlcnZlcigoZW50cmllcykgPT4ge1xuICAgIGVudHJpZXMuZm9yRWFjaCgoZW50cnkpID0+IHtcbiAgICAgICAgY29uc3QgZWxlbWVudCA9IGVudHJ5LnRhcmdldDsgLy8gVHlwZSBhc3NlcnRpb25cbiAgICAgICAgaWYgKGVudHJ5LmlzSW50ZXJzZWN0aW5nICYmIGVudHJ5LmludGVyc2VjdGlvblJhdGlvID09PSAxKSB7XG4gICAgICAgICAgICBlbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJpcy12aXNpYmxlXCIpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKFwiaXMtdmlzaWJsZVwiKTtcbiAgICAgICAgfVxuICAgIH0pO1xufSwgeyB0aHJlc2hvbGQ6IDEgfSk7XG5jb25zdCB2aXNpYmxlRWxlbWVudHMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiLnZpc2libGVcIik7XG52aXNpYmxlRWxlbWVudHMuZm9yRWFjaChlbGVtZW50ID0+IHtcbiAgICBpZiAoZWxlbWVudCBpbnN0YW5jZW9mIEhUTUxFbGVtZW50KSB7IC8vIFR5cGUgZ3VhcmRcbiAgICAgICAgb2JzZXJ2ZXIub2JzZXJ2ZShlbGVtZW50KTtcbiAgICB9XG59KTtcbi8qKlxuICogT24gY2xpY2sgb2YgI2xlYWd1ZS1maWx0ZXJzIC5sZWFndWUtZmlsdGVyXG4gKiBnZXQgdmFsdWUgZnJvbSBkYXRhLWxlYWd1ZS1pZCBhdHRyaWJ1dGVcbiAqIEZpbmQgI2xlYWd1ZS1jb250YWluZXIgLmxlYWd1ZSB3aXRoIG1hdGNoaW5nIGRhdGEtbGVhZ3VlLWlkXG4gKiBIaWRlIGFsbCBvdGhlciAubGVhZ3VlIGVsZW1lbnRzXG4gKiBTaG93IHRoZSBtYXRjaGluZyAubGVhZ3VlIGVsZW1lbnRcbiAqL1xuY29uc3QgbGVhZ3VlRmlsdGVycyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJyNsZWFndWUtZmlsdGVycyAubGVhZ3VlLWZpbHRlcicpO1xuY29uc3QgbGVhZ3VlQ29udGFpbmVycyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJyNsZWFndWVzLXNlY3Rpb24gLmxlYWd1ZScpO1xuLy8gY2xpY2sgZXZlbnRcbmxlYWd1ZUZpbHRlcnMuZm9yRWFjaChmaWx0ZXIgPT4ge1xuICAgIGZpbHRlci5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsICgpID0+IHtcbiAgICAgICAgLy8gZ2l2ZSB0aGUgY2xpY2tlZCBmaWx0ZXIgdGhlIGFjdGl2ZSBjbGFzc1xuICAgICAgICBsZWFndWVGaWx0ZXJzLmZvckVhY2goZmlsdGVyID0+IGZpbHRlci5jbGFzc0xpc3QucmVtb3ZlKCdhY3RpdmUnKSk7XG4gICAgICAgIGZpbHRlci5jbGFzc0xpc3QuYWRkKCdhY3RpdmUnKTtcbiAgICAgICAgLy8gZ2V0IHRoZSBsZWFndWVJZCBmcm9tIHRoZSBjbGlja2VkIGZpbHRlclxuICAgICAgICBjb25zdCBsZWFndWVJZCA9IGZpbHRlci5nZXRBdHRyaWJ1dGUoJ2RhdGEtbGVhZ3VlLWlkJyk7XG4gICAgICAgIGlmIChsZWFndWVJZCkge1xuICAgICAgICAgICAgLy8gaWYgbGVhZ3VlSWQgaXMgZXF1YWwgdG8gXCJhbGxcIiwgc2hvdyBhbGwgbGVhZ3Vlc1xuICAgICAgICAgICAgaWYgKGxlYWd1ZUlkID09PSAnYWxsJykge1xuICAgICAgICAgICAgICAgIGxlYWd1ZUNvbnRhaW5lcnMuZm9yRWFjaChsZWFndWUgPT4ge1xuICAgICAgICAgICAgICAgICAgICBjb25zdCBsZWFndWVFbGVtZW50ID0gbGVhZ3VlO1xuICAgICAgICAgICAgICAgICAgICBsZWFndWVFbGVtZW50LnN0eWxlLmRpc3BsYXkgPSAnYmxvY2snO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgLy8gZWxzZSwgc2hvdyBvbmx5IHRoZSBsZWFndWUgd2l0aCB0aGUgbWF0Y2hpbmcgbGVhZ3VlSWRcbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIGxlYWd1ZUNvbnRhaW5lcnMuZm9yRWFjaChsZWFndWUgPT4ge1xuICAgICAgICAgICAgICAgICAgICBjb25zdCBsZWFndWVFbGVtZW50ID0gbGVhZ3VlO1xuICAgICAgICAgICAgICAgICAgICBpZiAobGVhZ3VlRWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtbGVhZ3VlLWlkJykgPT09IGxlYWd1ZUlkKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBsZWFndWVFbGVtZW50LnN0eWxlLmRpc3BsYXkgPSAnYmxvY2snO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgbGVhZ3VlRWxlbWVudC5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9KTtcbn0pO1xuLyoqXG4gKiBQcm9ncmFtIHNlYXJjaCBmaWx0ZXJzXG4gKi9cbmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJET01Db250ZW50TG9hZGVkXCIsICgpID0+IHtcbiAgICBjb25zdCBmaWx0ZXJzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbChcIiNmaWx0ZXJzIHNlbGVjdFwiKTtcbiAgICBhcHBseUZpbHRlcnMoKTtcbiAgICBmaWx0ZXJzLmZvckVhY2goZmlsdGVyID0+IHtcbiAgICAgICAgZmlsdGVyLmFkZEV2ZW50TGlzdGVuZXIoXCJjaGFuZ2VcIiwgYXBwbHlGaWx0ZXJzKTtcbiAgICB9KTtcbiAgICBmdW5jdGlvbiBhcHBseUZpbHRlcnMoKSB7XG4gICAgICAgIGNvbnN0IGdldEZpbHRlclZhbHVlID0gKGlkKSA9PiB7XG4gICAgICAgICAgICBjb25zdCBlbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoaWQpO1xuICAgICAgICAgICAgcmV0dXJuIGVsZW1lbnQgPyBlbGVtZW50LnZhbHVlIDogXCJcIjtcbiAgICAgICAgfTtcbiAgICAgICAgY29uc3Qgc2VsZWN0ZWRGaWx0ZXJzID0ge1xuICAgICAgICAgICAgc3RhdGU6IGdldEZpbHRlclZhbHVlKFwic3RhdGVcIiksXG4gICAgICAgICAgICBjaXR5OiBnZXRGaWx0ZXJWYWx1ZShcImNpdHlcIiksXG4gICAgICAgICAgICBwcm9ncmFtczogZ2V0RmlsdGVyVmFsdWUoXCJwcm9ncmFtc1wiKSxcbiAgICAgICAgICAgIHNlYXNvbjogZ2V0RmlsdGVyVmFsdWUoXCJzZWFzb25cIiksXG4gICAgICAgICAgICBhZ2U6IGdldEZpbHRlclZhbHVlKFwiYWdlXCIpLFxuICAgICAgICAgICAgZ2VuZGVyOiBnZXRGaWx0ZXJWYWx1ZShcImdlbmRlclwiKVxuICAgICAgICB9O1xuICAgICAgICBjb25zdCBwcm9ncmFtcyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCIucHJvZ3JhbVwiKTtcbiAgICAgICAgcHJvZ3JhbXMuZm9yRWFjaChwcm9ncmFtID0+IHtcbiAgICAgICAgICAgIHZhciBfYSwgX2IsIF9jLCBfZCwgX2UsIF9mO1xuICAgICAgICAgICAgY29uc3QgcHJvZ3JhbUVsZW1lbnQgPSBwcm9ncmFtO1xuICAgICAgICAgICAgY29uc3Qgc3RhdGUgPSAoKF9hID0gcHJvZ3JhbUVsZW1lbnQuZGF0YXNldC5zdGF0ZSkgPT09IG51bGwgfHwgX2EgPT09IHZvaWQgMCA/IHZvaWQgMCA6IF9hLnRyaW0oKSkgfHwgXCJcIjtcbiAgICAgICAgICAgIGNvbnN0IGNpdHkgPSAoKF9iID0gcHJvZ3JhbUVsZW1lbnQuZGF0YXNldC5jaXR5KSA9PT0gbnVsbCB8fCBfYiA9PT0gdm9pZCAwID8gdm9pZCAwIDogX2IudHJpbSgpKSB8fCBcIlwiO1xuICAgICAgICAgICAgY29uc3QgcHJvZ3JhbXMgPSAoKF9jID0gcHJvZ3JhbUVsZW1lbnQuZGF0YXNldC5wcm9ncmFtcykgPT09IG51bGwgfHwgX2MgPT09IHZvaWQgMCA/IHZvaWQgMCA6IF9jLnRyaW0oKSkgfHwgXCJcIjtcbiAgICAgICAgICAgIGNvbnN0IHNlYXNvbiA9ICgoX2QgPSBwcm9ncmFtRWxlbWVudC5kYXRhc2V0LnNlYXNvbikgPT09IG51bGwgfHwgX2QgPT09IHZvaWQgMCA/IHZvaWQgMCA6IF9kLnRyaW0oKSkgfHwgXCJcIjtcbiAgICAgICAgICAgIGNvbnN0IGFnZXMgPSAoKF9lID0gcHJvZ3JhbUVsZW1lbnQuZGF0YXNldC5hZ2VzKSA9PT0gbnVsbCB8fCBfZSA9PT0gdm9pZCAwID8gdm9pZCAwIDogX2Uuc3BsaXQoXCIsXCIpLm1hcChhZ2UgPT4gYWdlLnRyaW0oKSkpIHx8IFtdO1xuICAgICAgICAgICAgY29uc3QgZ2VuZGVyID0gKChfZiA9IHByb2dyYW1FbGVtZW50LmRhdGFzZXQuZ2VuZGVyKSA9PT0gbnVsbCB8fCBfZiA9PT0gdm9pZCAwID8gdm9pZCAwIDogX2YudHJpbSgpKSB8fCBcIlwiO1xuICAgICAgICAgICAgbGV0IG1hdGNoZXMgPSB0cnVlO1xuICAgICAgICAgICAgaWYgKHNlbGVjdGVkRmlsdGVycy5zdGF0ZSAmJiBzdGF0ZSAhPT0gc2VsZWN0ZWRGaWx0ZXJzLnN0YXRlKVxuICAgICAgICAgICAgICAgIG1hdGNoZXMgPSBmYWxzZTtcbiAgICAgICAgICAgIGlmIChzZWxlY3RlZEZpbHRlcnMuY2l0eSAmJiBjaXR5ICE9PSBzZWxlY3RlZEZpbHRlcnMuY2l0eSlcbiAgICAgICAgICAgICAgICBtYXRjaGVzID0gZmFsc2U7XG4gICAgICAgICAgICBpZiAoc2VsZWN0ZWRGaWx0ZXJzLnByb2dyYW1zICYmIHByb2dyYW1zICE9PSBzZWxlY3RlZEZpbHRlcnMucHJvZ3JhbXMpXG4gICAgICAgICAgICAgICAgbWF0Y2hlcyA9IGZhbHNlO1xuICAgICAgICAgICAgaWYgKHNlbGVjdGVkRmlsdGVycy5zZWFzb24gJiYgc2Vhc29uICE9PSBzZWxlY3RlZEZpbHRlcnMuc2Vhc29uKVxuICAgICAgICAgICAgICAgIG1hdGNoZXMgPSBmYWxzZTtcbiAgICAgICAgICAgIGlmIChzZWxlY3RlZEZpbHRlcnMuYWdlICYmICFhZ2VzLmluY2x1ZGVzKHNlbGVjdGVkRmlsdGVycy5hZ2UpKVxuICAgICAgICAgICAgICAgIG1hdGNoZXMgPSBmYWxzZTtcbiAgICAgICAgICAgIGlmIChzZWxlY3RlZEZpbHRlcnMuZ2VuZGVyICYmIGdlbmRlciAhPT0gc2VsZWN0ZWRGaWx0ZXJzLmdlbmRlcilcbiAgICAgICAgICAgICAgICBtYXRjaGVzID0gZmFsc2U7XG4gICAgICAgICAgICBwcm9ncmFtRWxlbWVudC5zdHlsZS5kaXNwbGF5ID0gbWF0Y2hlcyA/IFwiYmxvY2tcIiA6IFwibm9uZVwiO1xuICAgICAgICB9KTtcbiAgICB9XG59KTtcbi8qKlxuICogQWRqdXN0IHdpZHRoIG9mIHNlbGVjdCBlbGVtZW50IGJhc2VkIG9uIHNlbGVjdGVkIG9wdGlvblxuICovXG5mdW5jdGlvbiBhZGp1c3RTZWxlY3RXaWR0aChzZWxlY3QpIHtcbiAgICBjb25zdCBzZWxlY3RlZE9wdGlvbiA9IHNlbGVjdC5vcHRpb25zW3NlbGVjdC5zZWxlY3RlZEluZGV4XTtcbiAgICBjb25zdCB0ZW1wU3BhbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3NwYW4nKTtcbiAgICB0ZW1wU3Bhbi5zdHlsZS5mb250ID0gd2luZG93LmdldENvbXB1dGVkU3R5bGUoc2VsZWN0KS5mb250O1xuICAgIHRlbXBTcGFuLnN0eWxlLnZpc2liaWxpdHkgPSAnaGlkZGVuJztcbiAgICB0ZW1wU3Bhbi5zdHlsZS5wb3NpdGlvbiA9ICdhYnNvbHV0ZSc7XG4gICAgdGVtcFNwYW4udGV4dENvbnRlbnQgPSBzZWxlY3RlZE9wdGlvbi50ZXh0Q29udGVudDtcbiAgICBkb2N1bWVudC5ib2R5LmFwcGVuZENoaWxkKHRlbXBTcGFuKTtcbiAgICBzZWxlY3Quc3R5bGUud2lkdGggPSB0ZW1wU3Bhbi5vZmZzZXRXaWR0aCAqIDEuMiArICdweCc7XG4gICAgZG9jdW1lbnQuYm9keS5yZW1vdmVDaGlsZCh0ZW1wU3Bhbik7XG59XG5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgKCkgPT4ge1xuICAgIGNvbnN0IHNlbGVjdEVsZW1lbnQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcjZmluZC1wcm9ncmFtLXNlbGVjdCcpO1xuICAgIGNvbnN0IGZpbmRQcm9ncmFtQnV0dG9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignI2ZpbmQtcHJvZ3JhbS1idXR0b24nKTtcbiAgICBpZiAoc2VsZWN0RWxlbWVudCAmJiBmaW5kUHJvZ3JhbUJ1dHRvbikge1xuICAgICAgICBzZWxlY3RFbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsICgpID0+IHtcbiAgICAgICAgICAgIGFkanVzdFNlbGVjdFdpZHRoKHNlbGVjdEVsZW1lbnQpO1xuICAgICAgICAgICAgY29uc3Qgc2VsZWN0ZWRWYWx1ZSA9IHNlbGVjdEVsZW1lbnQudmFsdWUucmVwbGFjZSgvXFxzL2csICctJykudG9Mb3dlckNhc2UoKTtcbiAgICAgICAgICAgIGNvbnN0IGJhc2VVcmwgPSAnL3Byb2dyYW0tc2VhcmNoJztcbiAgICAgICAgICAgIGlmIChzZWxlY3RlZFZhbHVlKSB7XG4gICAgICAgICAgICAgICAgZmluZFByb2dyYW1CdXR0b24uaHJlZiA9IGAke2Jhc2VVcmx9Lz9zdGF0ZT0ke3NlbGVjdGVkVmFsdWV9YDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIGZpbmRQcm9ncmFtQnV0dG9uLmhyZWYgPSBiYXNlVXJsOyAvLyBSZXNldCBpZiBubyB2YWx1ZSBzZWxlY3RlZFxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgYWRqdXN0U2VsZWN0V2lkdGgoc2VsZWN0RWxlbWVudCk7IC8vIEluaXRpYWwgd2lkdGggc2V0LlxuICAgIH1cbn0pO1xuIl0sIm5hbWVzIjpbXSwic291cmNlUm9vdCI6IiJ9