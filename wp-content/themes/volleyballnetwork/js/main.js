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
 * Search page results filtering
 */
const resultFilters = document.querySelectorAll('#search-result-filters .result-filter');
const resultListings = document.querySelectorAll('#search-results-section .result');
// click event
resultFilters.forEach(filter => {
    filter.addEventListener('click', () => {
        // give the clicked filter the active class
        resultFilters.forEach(filter => filter.classList.remove('active'));
        filter.classList.add('active');
        // get the resultId from the clicked filter
        const resultId = filter.getAttribute('data-post-type');
        if (resultId) {
            // if resultId is equal to "all", show all results
            if (resultId === 'all') {
                resultListings.forEach(result => {
                    const resultElement = result;
                    resultElement.style.display = 'block';
                });
            }
            // else, show only the result with the matching result
            else {
                resultListings.forEach(result => {
                    const resultElement = result;
                    if (resultElement.getAttribute('data-post-type') === resultId) {
                        resultElement.style.display = 'block';
                    }
                    else {
                        resultElement.style.display = 'none';
                    }
                });
            }
            // apply odd and even classes to all visible results
            var visiblePosition = 'odd';
            resultListings.forEach((result, index) => {
                const resultElement = result;
                resultElement.classList.remove('odd', 'even');
                if (resultElement.style.display === 'block') {
                    // alternate odd and even classes
                    if (visiblePosition === 'odd') {
                        resultElement.classList.add('odd');
                        visiblePosition = 'even';
                    }
                    else {
                        resultElement.classList.add('even');
                        visiblePosition = 'odd';
                    }
                }
            });
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
/**
 * Toggle filters for programs
 */
document.addEventListener("DOMContentLoaded", () => {
    const toggleButton = document.getElementById("toggle-filters");
    const filters = document.getElementById("filters");
    if (toggleButton && filters) {
        toggleButton.addEventListener("click", () => {
            filters.classList.toggle("open");
        });
    }
});

/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBLEtBQUs7QUFDTCxDQUFDO0FBQ0Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLENBQUM7QUFDRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsNENBQTRDLFFBQVEsVUFBVSxjQUFjO0FBQzVFO0FBQ0E7QUFDQSxrREFBa0Q7QUFDbEQ7QUFDQSxTQUFTO0FBQ1QsMENBQTBDO0FBQzFDO0FBQ0EsQ0FBQztBQUNEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBLENBQUMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9jb21wbGV0ZS1zYXNzLXR1dG9yaWFsLy4vc3JjL21haW4udHMiXSwic291cmNlc0NvbnRlbnQiOlsiXCJ1c2Ugc3RyaWN0XCI7XG4vKlxuICBHTE9CQUwgRlVOQ1RJT05TXG4qL1xuZnVuY3Rpb24gY2xpY2tPdXRzaWRlRWxlbWVudChlbGVtZW50LCBjYWxsYmFjaywgZXhjZXB0aW9uRWxlbWVudCA9IG51bGwpIHtcbiAgICBpZiAoIWVsZW1lbnQpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgIH1cbiAgICBjb25zdCBvdXRzaWRlQ2xpY2tMaXN0ZW5lciA9IChldmVudCkgPT4ge1xuICAgICAgICBjb25zdCB0YXJnZXQgPSBldmVudC50YXJnZXQ7IC8vIENhc3QgdG8gTm9kZSBmb3IgY29udGFpbnMoKVxuICAgICAgICBpZiAoZWxlbWVudCAmJiAhZWxlbWVudC5jb250YWlucyh0YXJnZXQpICYmICghZXhjZXB0aW9uRWxlbWVudCB8fCAhZXhjZXB0aW9uRWxlbWVudC5jb250YWlucyh0YXJnZXQpKSkge1xuICAgICAgICAgICAgY2FsbGJhY2soKTtcbiAgICAgICAgfVxuICAgIH07XG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBvdXRzaWRlQ2xpY2tMaXN0ZW5lcik7XG4gICAgcmV0dXJuICgpID0+IHtcbiAgICAgICAgZG9jdW1lbnQucmVtb3ZlRXZlbnRMaXN0ZW5lcignY2xpY2snLCBvdXRzaWRlQ2xpY2tMaXN0ZW5lcik7XG4gICAgfTtcbn1cbi8qKlxuICogSWYgcGFnZSBpcyBub3QgYXQgdG9wLCBhZGQgc2Nyb2xsaW5nIGNsYXNzIHRvIGJvZHlcbiAqIElmIHBhZ2UgaXMgc2Nyb2xsaW5nIHVwLCBhZGQgc2Nyb2xsaW5nLXVwIGNsYXNzIHRvIGJvZHlcbiAqXG5sZXQgbGFzdFNjcm9sbFRvcCA9IDA7XG53aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignc2Nyb2xsJywgKCkgPT4ge1xuICBjb25zdCBib2R5ID0gZG9jdW1lbnQuYm9keTtcbiAgY29uc3Qgc2Nyb2xsVG9wID0gd2luZG93LnBhZ2VZT2Zmc2V0IHx8IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5zY3JvbGxUb3A7XG4gIGlmIChzY3JvbGxUb3AgPiAwKSB7XG4gICAgYm9keS5jbGFzc0xpc3QuYWRkKCdzY3JvbGxpbmcnKTtcbiAgfSBlbHNlIHtcbiAgICBib2R5LmNsYXNzTGlzdC5yZW1vdmUoJ3Njcm9sbGluZycpO1xuICB9XG4gIGlmIChzY3JvbGxUb3AgPiBsYXN0U2Nyb2xsVG9wKSB7XG4gICAgYm9keS5jbGFzc0xpc3QucmVtb3ZlKCdzY3JvbGxpbmctdXAnKTtcbiAgfSBlbHNlIHtcbiAgICBib2R5LmNsYXNzTGlzdC5hZGQoJ3Njcm9sbGluZy11cCcpO1xuICB9XG4gIGxhc3RTY3JvbGxUb3AgPSBzY3JvbGxUb3A7XG59KTtcblxuXG4vKlxuICBTRUFSQ0ggRk9STSBBTklNQVRJT05cbiovXG5jb25zdCBzZWFyY2hGb3JtID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnNlYXJjaC1mb3JtJyk7XG5jb25zdCBzZWFyY2hGaWVsZCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5zZWFyY2gtZmllbGQnKTtcbi8vIENoZWNrIGlmIGVsZW1lbnRzIGV4aXN0XG5pZiAoc2VhcmNoRm9ybSAmJiBzZWFyY2hGaWVsZCkge1xuICAgIHNlYXJjaEZpZWxkLmFkZEV2ZW50TGlzdGVuZXIoJ2ZvY3VzJywgKCkgPT4ge1xuICAgICAgICBzZWFyY2hGb3JtLnN0eWxlLndpZHRoID0gJzIwMHB4JztcbiAgICB9KTtcbiAgICBzZWFyY2hGaWVsZC5hZGRFdmVudExpc3RlbmVyKCdibHVyJywgKCkgPT4ge1xuICAgICAgICBzZWFyY2hGb3JtLnN0eWxlLnJlbW92ZVByb3BlcnR5KFwid2lkdGhcIik7XG4gICAgfSk7XG59XG4vKlxuICBNRU5VIFNFVFVQXG4qXG5jb25zdCBtZW51QnV0dG9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIiNtZW51LWJ1dHRvblwiKSBhcyBIVE1MRWxlbWVudCB8IG51bGw7XG5jb25zdCBwcmltYXJ5TWVudSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoXCIjcHJpbWFyeS1tZW51XCIpIGFzIEhUTUxFbGVtZW50IHwgbnVsbDtcbmNvbnN0IGJhcnMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiI21lbnUtYnV0dG9uIC5iYXJcIik7XG5jb25zdCBzdWJNZW51cyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCIjcHJpbWFyeS1tZW51IC5zdWItbWVudVwiKTtcbmNvbnN0IG1lbnVUb2dnbGVzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnI3ByaW1hcnktbWVudSAubWVudS10b2dnbGUnKTtcblxuZnVuY3Rpb24gY2xvc2VNZW51KCl7XG4gIG1lbnVUb2dnbGVzLmZvckVhY2gobWVudVRvZ2dsZSA9PiBtZW51VG9nZ2xlLmNsYXNzTGlzdC5yZW1vdmUoXCJvblwiKSk7XG4gIHN1Yk1lbnVzLmZvckVhY2goc3ViTWVudSA9PiBzdWJNZW51LmNsYXNzTGlzdC5yZW1vdmUoXCJhY3RpdmVcIikpO1xuICBpZiAocHJpbWFyeU1lbnUpIHtcbiAgICBwcmltYXJ5TWVudS5jbGFzc0xpc3QucmVtb3ZlKFwib3BlblwiKTtcbiAgfVxuICBpZiAobWVudUJ1dHRvbikge1xuICAgIG1lbnVCdXR0b24uY2xhc3NMaXN0LnJlbW92ZShcImFjdGl2ZVwiKTtcbiAgfVxuICAvLyBFbmFibGUgc2Nyb2xsaW5nXG4gIGRvY3VtZW50LmJvZHkuc3R5bGUub3ZlcmZsb3dZID0gJ2F1dG8nO1xuICAvLyBSZXNldCBtZW51QnV0dG9uIHN0eWxlc1xuICBzZXRUaW1lb3V0KCgpID0+IHtcbiAgICBiYXJzLmZvckVhY2goYmFyID0+IGJhci5yZW1vdmVBdHRyaWJ1dGUoXCJzdHlsZVwiKSk7XG4gIH0sIDIwMCk7XG59XG5cbi8qXG4gIEFOSU1BVElPTlMgRk9SIE1FTlUgQlVUVE9OXG4qXG5tZW51VG9nZ2xlcy5mb3JFYWNoKG1lbnVUb2dnbGUgPT4gbWVudVRvZ2dsZS5jbGFzc0xpc3QucmVtb3ZlKFwib25cIikpO1xuaWYgKG1lbnVCdXR0b24gJiYgcHJpbWFyeU1lbnUpIHtcbiAgbWVudUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKCkgPT4ge1xuICAgIGlmIChwcmltYXJ5TWVudS5jbGFzc0xpc3QuY29udGFpbnMoXCJvcGVuXCIpKSB7XG4gICAgICAvLyBDbG9zaW5nIGFuaW1hdGlvblxuICAgICAgY2xvc2VNZW51KCk7XG4gICAgfSBlbHNlIHtcbiAgICAgIC8vIE9wZW5pbmcgYW5pbWF0aW9uXG4gICAgICBwcmltYXJ5TWVudS5jbGFzc0xpc3QuYWRkKFwib3BlblwiKTtcbiAgICAgIG1lbnVCdXR0b24uY2xhc3NMaXN0LmFkZChcImFjdGl2ZVwiKTtcbiAgICAgIC8vIERpc2FibGUgc2Nyb2xsaW5nXG4gICAgICBkb2N1bWVudC5ib2R5LnN0eWxlLm92ZXJmbG93WSA9ICdoaWRkZW4nO1xuICAgIH1cbiAgfSk7XG59XG5pZiAocHJpbWFyeU1lbnUpIHtcbiAgY29uc3QgcmVtb3ZlQ2xpY2tMaXN0ZW5lciA9IGNsaWNrT3V0c2lkZUVsZW1lbnQocHJpbWFyeU1lbnUsICgpID0+IHtcbiAgICBjb25zb2xlLmxvZygnQ2xpY2tlZCBvdXRzaWRlIHRoZSBwcmltYXJ5IG1lbnUhJyk7XG4gICAgY2xvc2VNZW51KCk7XG4gIH0sIG1lbnVCdXR0b24pOyAvLyBQYXNzIHRoZSBtZW51QnV0dG9uIGFzIHRoZSB0aGlyZCBhcmd1bWVudFxufSBlbHNlIHtcbiAgY29uc29sZS5lcnJvcihcIlByaW1hcnkgbWVudSBlbGVtZW50IG5vdCBmb3VuZC5cIik7XG59XG5cbi8qXG4gIE1FTlUgRVhQQU5USU9OIE9OIE1PQklMRVxuKlxuY29uc3QgbWVudUl0ZW1zID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLm1lbnUtaXRlbS1oYXMtY2hpbGRyZW4nKTtcblxubWVudUl0ZW1zLmZvckVhY2goaXRlbSA9PiB7XG4gIGNvbnN0IGJ1dHRvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3NwYW4nKTtcbiAgYnV0dG9uLmNsYXNzTGlzdC5hZGQoJ21lbnUtdG9nZ2xlJyk7XG5cbiAgY29uc3QgZmlyc3RMaW5rID0gaXRlbS5xdWVyeVNlbGVjdG9yKCdhJyk7XG4gIGlmIChmaXJzdExpbmspIHtcbiAgICBpdGVtLmluc2VydEJlZm9yZShidXR0b24sIGZpcnN0TGluayk7XG4gIH0gZWxzZSB7XG4gICAgaXRlbS5hcHBlbmRDaGlsZChidXR0b24pO1xuICB9XG5cbiAgYnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKGV2ZW50KSA9PiB7XG4gICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcblxuICAgIGNvbnN0IGZpcnN0U3ViTWVudSA9IGl0ZW0ucXVlcnlTZWxlY3RvcignLnN1Yi1tZW51Jyk7XG5cbiAgICBpZiAoZmlyc3RTdWJNZW51KSB7XG4gICAgICBmaXJzdFN1Yk1lbnUuY2xhc3NMaXN0LnRvZ2dsZSgnYWN0aXZlJyk7XG4gICAgICBidXR0b24uY2xhc3NMaXN0LnRvZ2dsZSgnb24nKTsgLy8gVG9nZ2xlIHRoZSAnb24nIGNsYXNzIG9uIHRoZSBidXR0b25cblxuICAgICAgLy8gQ2xvc2Ugb3RoZXIgb3BlbiBzdWItbWVudXMgYW5kIHVwZGF0ZSBidXR0b24gc3RhdGVzXG4gICAgICBjb25zdCBwYXJlbnRTdWJNZW51ID0gaXRlbS5wYXJlbnRFbGVtZW50O1xuICAgICAgaWYgKHBhcmVudFN1Yk1lbnUpIHtcbiAgICAgICAgY29uc3Qgc2libGluZ01lbnVJdGVtcyA9IEFycmF5LmZyb20ocGFyZW50U3ViTWVudS5jaGlsZHJlbikuZmlsdGVyKGNoaWxkID0+IGNoaWxkICE9PSBpdGVtICYmIGNoaWxkLmNsYXNzTGlzdC5jb250YWlucygnbWVudS1pdGVtLWhhcy1jaGlsZHJlbicpKTtcblxuICAgICAgICBzaWJsaW5nTWVudUl0ZW1zLmZvckVhY2goc2libGluZyA9PiB7XG4gICAgICAgICAgY29uc3Qgc2libGluZ1N1Yk1lbnUgPSBzaWJsaW5nLnF1ZXJ5U2VsZWN0b3IoJy5zdWItbWVudScpO1xuICAgICAgICAgIGNvbnN0IHNpYmxpbmdCdXR0b24gPSBzaWJsaW5nLnF1ZXJ5U2VsZWN0b3IoJy5tZW51LXRvZ2dsZScpO1xuICAgICAgICAgIGlmIChzaWJsaW5nU3ViTWVudSAmJiBzaWJsaW5nQnV0dG9uKSB7XG4gICAgICAgICAgICBzaWJsaW5nU3ViTWVudS5jbGFzc0xpc3QucmVtb3ZlKCdhY3RpdmUnKTtcbiAgICAgICAgICAgIHNpYmxpbmdCdXR0b24uY2xhc3NMaXN0LnJlbW92ZSgnb24nKTsgLy8gUmVtb3ZlICdvbicgY2xhc3MgZnJvbSBzaWJsaW5nIGJ1dHRvbnNcbiAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgfVxuICAgIH1cbiAgfSk7XG59KTtcblxuLypcbiAgRVFVQUwgSEVJR0hUUyBDRUxMU1xuKi9cbmZ1bmN0aW9uIGVxdWFsaXplQ2VsbEhlaWdodHMoKSB7XG4gICAgY29uc3QgY2VsbFJvd3MgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcuY2VsbC1yb3cnKTtcbiAgICBjZWxsUm93cy5mb3JFYWNoKHJvdyA9PiB7XG4gICAgICAgIGNvbnN0IGNlbGxzID0gcm93LnF1ZXJ5U2VsZWN0b3JBbGwoJy5jZWxsJyk7XG4gICAgICAgIGxldCBtYXhIZWlnaHQgPSAwO1xuICAgICAgICBjZWxscy5mb3JFYWNoKGNlbGwgPT4ge1xuICAgICAgICAgICAgY29uc3QgaHRtbENlbGwgPSBjZWxsOyAvLyBDYXN0IHRvIEhUTUxFbGVtZW50XG4gICAgICAgICAgICBodG1sQ2VsbC5zdHlsZS5oZWlnaHQgPSAnYXV0byc7XG4gICAgICAgICAgICBtYXhIZWlnaHQgPSBNYXRoLm1heChtYXhIZWlnaHQsIGh0bWxDZWxsLm9mZnNldEhlaWdodCk7XG4gICAgICAgIH0pO1xuICAgICAgICBjZWxscy5mb3JFYWNoKGNlbGwgPT4ge1xuICAgICAgICAgICAgY29uc3QgaHRtbENlbGwgPSBjZWxsOyAvLyBDYXN0IHRvIEhUTUxFbGVtZW50XG4gICAgICAgICAgICBodG1sQ2VsbC5zdHlsZS5oZWlnaHQgPSBgJHttYXhIZWlnaHR9cHhgO1xuICAgICAgICB9KTtcbiAgICB9KTtcbn1cbi8vIENhbGwgaW5pdGlhbGx5IG9uIHBhZ2UgbG9hZFxud2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCBlcXVhbGl6ZUNlbGxIZWlnaHRzKTtcbi8vIENhbGwgb24gd2luZG93IHJlc2l6ZVxubGV0IHJlc2l6ZVRpbWVvdXQgPSBudWxsO1xud2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3Jlc2l6ZScsICgpID0+IHtcbiAgICBpZiAocmVzaXplVGltZW91dCkge1xuICAgICAgICBjbGVhclRpbWVvdXQocmVzaXplVGltZW91dCk7XG4gICAgfVxuICAgIHJlc2l6ZVRpbWVvdXQgPSBzZXRUaW1lb3V0KGVxdWFsaXplQ2VsbEhlaWdodHMsIDIwMCk7XG59KTtcbi8qXG4gIENPTlRFTlQgSU4gVklFV1BPUlRcbiovXG5mdW5jdGlvbiBpc0Z1bGx5SW5WaWV3cG9ydFJvYnVzdChlbGVtZW50KSB7XG4gICAgY29uc3QgcmVjdCA9IGVsZW1lbnQuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XG4gICAgcmV0dXJuIChyZWN0LnRvcCA+PSAwICYmXG4gICAgICAgIHJlY3QubGVmdCA+PSAwICYmXG4gICAgICAgIHJlY3QuYm90dG9tIDw9ICh3aW5kb3cuaW5uZXJIZWlnaHQgfHwgZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNsaWVudEhlaWdodCkgJiZcbiAgICAgICAgcmVjdC5yaWdodCA8PSAod2luZG93LmlubmVyV2lkdGggfHwgZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNsaWVudFdpZHRoKSk7XG59XG5mdW5jdGlvbiBoYW5kbGVWaXNpYmlsaXR5Q2hlY2soKSB7XG4gICAgY29uc3QgZWxlbWVudHMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiLnZpc2libGVcIik7IC8vIEdldCBhbGwgZWxlbWVudHMgd2l0aCB0aGUgY2xhc3MgXCJ2aXNpYmxlXCJcbiAgICBlbGVtZW50cy5mb3JFYWNoKChlbGVtZW50KSA9PiB7XG4gICAgICAgIGlmIChlbGVtZW50IGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpIHsgLy8gVHlwZSBndWFyZCB0byBlbnN1cmUgaXQncyBhbiBIVE1MRWxlbWVudFxuICAgICAgICAgICAgaWYgKGlzRnVsbHlJblZpZXdwb3J0Um9idXN0KGVsZW1lbnQpKSB7XG4gICAgICAgICAgICAgICAgLy8gRG8gc29tZXRoaW5nLCBlLmcuLCBhZGQgYSBjbGFzcywgY2hhbmdlIHN0eWxlcywgZXRjLlxuICAgICAgICAgICAgICAgIGVsZW1lbnQuY2xhc3NMaXN0LmFkZChcImlzLXZpc2libGVcIik7IC8vIEV4YW1wbGU6IGFkZCBhIGNsYXNzXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBlbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJpcy12aXNpYmxlXCIpOyAvLyBFeGFtcGxlOiByZW1vdmUgdGhlIGNsYXNzXG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9KTtcbn1cbi8vIEluaXRpYWwgY2hlY2sgd2hlbiB0aGUgcGFnZSBsb2FkczpcbmhhbmRsZVZpc2liaWxpdHlDaGVjaygpO1xuLy8gQ2hlY2sgb24gc2Nyb2xsOlxud2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3Njcm9sbCcsIGhhbmRsZVZpc2liaWxpdHlDaGVjayk7XG4vLyBDaGVjayBvbiByZXNpemUgKGltcG9ydGFudCBmb3Igdmlld3BvcnQgY2hhbmdlcyk6XG53aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcigncmVzaXplJywgaGFuZGxlVmlzaWJpbGl0eUNoZWNrKTtcbi8vIEV4YW1wbGUgdXNpbmcgSW50ZXJzZWN0aW9uIE9ic2VydmVyIChNb3JlIHBlcmZvcm1hbnQgZm9yIGZyZXF1ZW50IGNoYW5nZXMpOlxuY29uc3Qgb2JzZXJ2ZXIgPSBuZXcgSW50ZXJzZWN0aW9uT2JzZXJ2ZXIoKGVudHJpZXMpID0+IHtcbiAgICBlbnRyaWVzLmZvckVhY2goKGVudHJ5KSA9PiB7XG4gICAgICAgIGNvbnN0IGVsZW1lbnQgPSBlbnRyeS50YXJnZXQ7IC8vIFR5cGUgYXNzZXJ0aW9uXG4gICAgICAgIGlmIChlbnRyeS5pc0ludGVyc2VjdGluZyAmJiBlbnRyeS5pbnRlcnNlY3Rpb25SYXRpbyA9PT0gMSkge1xuICAgICAgICAgICAgZWxlbWVudC5jbGFzc0xpc3QuYWRkKFwiaXMtdmlzaWJsZVwiKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIGVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcImlzLXZpc2libGVcIik7XG4gICAgICAgIH1cbiAgICB9KTtcbn0sIHsgdGhyZXNob2xkOiAxIH0pO1xuY29uc3QgdmlzaWJsZUVsZW1lbnRzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbChcIi52aXNpYmxlXCIpO1xudmlzaWJsZUVsZW1lbnRzLmZvckVhY2goZWxlbWVudCA9PiB7XG4gICAgaWYgKGVsZW1lbnQgaW5zdGFuY2VvZiBIVE1MRWxlbWVudCkgeyAvLyBUeXBlIGd1YXJkXG4gICAgICAgIG9ic2VydmVyLm9ic2VydmUoZWxlbWVudCk7XG4gICAgfVxufSk7XG4vKipcbiAqIE9uIGNsaWNrIG9mICNsZWFndWUtZmlsdGVycyAubGVhZ3VlLWZpbHRlclxuICogZ2V0IHZhbHVlIGZyb20gZGF0YS1sZWFndWUtaWQgYXR0cmlidXRlXG4gKiBGaW5kICNsZWFndWUtY29udGFpbmVyIC5sZWFndWUgd2l0aCBtYXRjaGluZyBkYXRhLWxlYWd1ZS1pZFxuICogSGlkZSBhbGwgb3RoZXIgLmxlYWd1ZSBlbGVtZW50c1xuICogU2hvdyB0aGUgbWF0Y2hpbmcgLmxlYWd1ZSBlbGVtZW50XG4gKi9cbmNvbnN0IGxlYWd1ZUZpbHRlcnMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcjbGVhZ3VlLWZpbHRlcnMgLmxlYWd1ZS1maWx0ZXInKTtcbmNvbnN0IGxlYWd1ZUNvbnRhaW5lcnMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcjbGVhZ3Vlcy1zZWN0aW9uIC5sZWFndWUnKTtcbi8vIGNsaWNrIGV2ZW50XG5sZWFndWVGaWx0ZXJzLmZvckVhY2goZmlsdGVyID0+IHtcbiAgICBmaWx0ZXIuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoKSA9PiB7XG4gICAgICAgIC8vIGdpdmUgdGhlIGNsaWNrZWQgZmlsdGVyIHRoZSBhY3RpdmUgY2xhc3NcbiAgICAgICAgbGVhZ3VlRmlsdGVycy5mb3JFYWNoKGZpbHRlciA9PiBmaWx0ZXIuY2xhc3NMaXN0LnJlbW92ZSgnYWN0aXZlJykpO1xuICAgICAgICBmaWx0ZXIuY2xhc3NMaXN0LmFkZCgnYWN0aXZlJyk7XG4gICAgICAgIC8vIGdldCB0aGUgbGVhZ3VlSWQgZnJvbSB0aGUgY2xpY2tlZCBmaWx0ZXJcbiAgICAgICAgY29uc3QgbGVhZ3VlSWQgPSBmaWx0ZXIuZ2V0QXR0cmlidXRlKCdkYXRhLWxlYWd1ZS1pZCcpO1xuICAgICAgICBpZiAobGVhZ3VlSWQpIHtcbiAgICAgICAgICAgIC8vIGlmIGxlYWd1ZUlkIGlzIGVxdWFsIHRvIFwiYWxsXCIsIHNob3cgYWxsIGxlYWd1ZXNcbiAgICAgICAgICAgIGlmIChsZWFndWVJZCA9PT0gJ2FsbCcpIHtcbiAgICAgICAgICAgICAgICBsZWFndWVDb250YWluZXJzLmZvckVhY2gobGVhZ3VlID0+IHtcbiAgICAgICAgICAgICAgICAgICAgY29uc3QgbGVhZ3VlRWxlbWVudCA9IGxlYWd1ZTtcbiAgICAgICAgICAgICAgICAgICAgbGVhZ3VlRWxlbWVudC5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIC8vIGVsc2UsIHNob3cgb25seSB0aGUgbGVhZ3VlIHdpdGggdGhlIG1hdGNoaW5nIGxlYWd1ZUlkXG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBsZWFndWVDb250YWluZXJzLmZvckVhY2gobGVhZ3VlID0+IHtcbiAgICAgICAgICAgICAgICAgICAgY29uc3QgbGVhZ3VlRWxlbWVudCA9IGxlYWd1ZTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKGxlYWd1ZUVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLWxlYWd1ZS1pZCcpID09PSBsZWFndWVJZCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbGVhZ3VlRWxlbWVudC5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGxlYWd1ZUVsZW1lbnQuc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSk7XG59KTtcbi8qKlxuICogU2VhcmNoIHBhZ2UgcmVzdWx0cyBmaWx0ZXJpbmdcbiAqL1xuY29uc3QgcmVzdWx0RmlsdGVycyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJyNzZWFyY2gtcmVzdWx0LWZpbHRlcnMgLnJlc3VsdC1maWx0ZXInKTtcbmNvbnN0IHJlc3VsdExpc3RpbmdzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnI3NlYXJjaC1yZXN1bHRzLXNlY3Rpb24gLnJlc3VsdCcpO1xuLy8gY2xpY2sgZXZlbnRcbnJlc3VsdEZpbHRlcnMuZm9yRWFjaChmaWx0ZXIgPT4ge1xuICAgIGZpbHRlci5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsICgpID0+IHtcbiAgICAgICAgLy8gZ2l2ZSB0aGUgY2xpY2tlZCBmaWx0ZXIgdGhlIGFjdGl2ZSBjbGFzc1xuICAgICAgICByZXN1bHRGaWx0ZXJzLmZvckVhY2goZmlsdGVyID0+IGZpbHRlci5jbGFzc0xpc3QucmVtb3ZlKCdhY3RpdmUnKSk7XG4gICAgICAgIGZpbHRlci5jbGFzc0xpc3QuYWRkKCdhY3RpdmUnKTtcbiAgICAgICAgLy8gZ2V0IHRoZSByZXN1bHRJZCBmcm9tIHRoZSBjbGlja2VkIGZpbHRlclxuICAgICAgICBjb25zdCByZXN1bHRJZCA9IGZpbHRlci5nZXRBdHRyaWJ1dGUoJ2RhdGEtcG9zdC10eXBlJyk7XG4gICAgICAgIGlmIChyZXN1bHRJZCkge1xuICAgICAgICAgICAgLy8gaWYgcmVzdWx0SWQgaXMgZXF1YWwgdG8gXCJhbGxcIiwgc2hvdyBhbGwgcmVzdWx0c1xuICAgICAgICAgICAgaWYgKHJlc3VsdElkID09PSAnYWxsJykge1xuICAgICAgICAgICAgICAgIHJlc3VsdExpc3RpbmdzLmZvckVhY2gocmVzdWx0ID0+IHtcbiAgICAgICAgICAgICAgICAgICAgY29uc3QgcmVzdWx0RWxlbWVudCA9IHJlc3VsdDtcbiAgICAgICAgICAgICAgICAgICAgcmVzdWx0RWxlbWVudC5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIC8vIGVsc2UsIHNob3cgb25seSB0aGUgcmVzdWx0IHdpdGggdGhlIG1hdGNoaW5nIHJlc3VsdFxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgcmVzdWx0TGlzdGluZ3MuZm9yRWFjaChyZXN1bHQgPT4ge1xuICAgICAgICAgICAgICAgICAgICBjb25zdCByZXN1bHRFbGVtZW50ID0gcmVzdWx0O1xuICAgICAgICAgICAgICAgICAgICBpZiAocmVzdWx0RWxlbWVudC5nZXRBdHRyaWJ1dGUoJ2RhdGEtcG9zdC10eXBlJykgPT09IHJlc3VsdElkKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXN1bHRFbGVtZW50LnN0eWxlLmRpc3BsYXkgPSAnYmxvY2snO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgcmVzdWx0RWxlbWVudC5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAvLyBhcHBseSBvZGQgYW5kIGV2ZW4gY2xhc3NlcyB0byBhbGwgdmlzaWJsZSByZXN1bHRzXG4gICAgICAgICAgICB2YXIgdmlzaWJsZVBvc2l0aW9uID0gJ29kZCc7XG4gICAgICAgICAgICByZXN1bHRMaXN0aW5ncy5mb3JFYWNoKChyZXN1bHQsIGluZGV4KSA9PiB7XG4gICAgICAgICAgICAgICAgY29uc3QgcmVzdWx0RWxlbWVudCA9IHJlc3VsdDtcbiAgICAgICAgICAgICAgICByZXN1bHRFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoJ29kZCcsICdldmVuJyk7XG4gICAgICAgICAgICAgICAgaWYgKHJlc3VsdEVsZW1lbnQuc3R5bGUuZGlzcGxheSA9PT0gJ2Jsb2NrJykge1xuICAgICAgICAgICAgICAgICAgICAvLyBhbHRlcm5hdGUgb2RkIGFuZCBldmVuIGNsYXNzZXNcbiAgICAgICAgICAgICAgICAgICAgaWYgKHZpc2libGVQb3NpdGlvbiA9PT0gJ29kZCcpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJlc3VsdEVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnb2RkJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICB2aXNpYmxlUG9zaXRpb24gPSAnZXZlbic7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXN1bHRFbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2V2ZW4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZpc2libGVQb3NpdGlvbiA9ICdvZGQnO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICB9KTtcbn0pO1xuLyoqXG4gKiBQcm9ncmFtIHNlYXJjaCBmaWx0ZXJzXG4gKi9cbmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJET01Db250ZW50TG9hZGVkXCIsICgpID0+IHtcbiAgICBjb25zdCBmaWx0ZXJzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbChcIiNmaWx0ZXJzIHNlbGVjdFwiKTtcbiAgICBhcHBseUZpbHRlcnMoKTtcbiAgICBmaWx0ZXJzLmZvckVhY2goZmlsdGVyID0+IHtcbiAgICAgICAgZmlsdGVyLmFkZEV2ZW50TGlzdGVuZXIoXCJjaGFuZ2VcIiwgYXBwbHlGaWx0ZXJzKTtcbiAgICB9KTtcbiAgICBmdW5jdGlvbiBhcHBseUZpbHRlcnMoKSB7XG4gICAgICAgIGNvbnN0IGdldEZpbHRlclZhbHVlID0gKGlkKSA9PiB7XG4gICAgICAgICAgICBjb25zdCBlbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoaWQpO1xuICAgICAgICAgICAgcmV0dXJuIGVsZW1lbnQgPyBlbGVtZW50LnZhbHVlIDogXCJcIjtcbiAgICAgICAgfTtcbiAgICAgICAgY29uc3Qgc2VsZWN0ZWRGaWx0ZXJzID0ge1xuICAgICAgICAgICAgc3RhdGU6IGdldEZpbHRlclZhbHVlKFwic3RhdGVcIiksXG4gICAgICAgICAgICBjaXR5OiBnZXRGaWx0ZXJWYWx1ZShcImNpdHlcIiksXG4gICAgICAgICAgICBwcm9ncmFtczogZ2V0RmlsdGVyVmFsdWUoXCJwcm9ncmFtc1wiKSxcbiAgICAgICAgICAgIHNlYXNvbjogZ2V0RmlsdGVyVmFsdWUoXCJzZWFzb25cIiksXG4gICAgICAgICAgICBhZ2U6IGdldEZpbHRlclZhbHVlKFwiYWdlXCIpLFxuICAgICAgICAgICAgZ2VuZGVyOiBnZXRGaWx0ZXJWYWx1ZShcImdlbmRlclwiKVxuICAgICAgICB9O1xuICAgICAgICBjb25zdCBwcm9ncmFtcyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCIucHJvZ3JhbVwiKTtcbiAgICAgICAgcHJvZ3JhbXMuZm9yRWFjaChwcm9ncmFtID0+IHtcbiAgICAgICAgICAgIHZhciBfYSwgX2IsIF9jLCBfZCwgX2UsIF9mO1xuICAgICAgICAgICAgY29uc3QgcHJvZ3JhbUVsZW1lbnQgPSBwcm9ncmFtO1xuICAgICAgICAgICAgY29uc3Qgc3RhdGUgPSAoKF9hID0gcHJvZ3JhbUVsZW1lbnQuZGF0YXNldC5zdGF0ZSkgPT09IG51bGwgfHwgX2EgPT09IHZvaWQgMCA/IHZvaWQgMCA6IF9hLnRyaW0oKSkgfHwgXCJcIjtcbiAgICAgICAgICAgIGNvbnN0IGNpdHkgPSAoKF9iID0gcHJvZ3JhbUVsZW1lbnQuZGF0YXNldC5jaXR5KSA9PT0gbnVsbCB8fCBfYiA9PT0gdm9pZCAwID8gdm9pZCAwIDogX2IudHJpbSgpKSB8fCBcIlwiO1xuICAgICAgICAgICAgY29uc3QgcHJvZ3JhbXMgPSAoKF9jID0gcHJvZ3JhbUVsZW1lbnQuZGF0YXNldC5wcm9ncmFtcykgPT09IG51bGwgfHwgX2MgPT09IHZvaWQgMCA/IHZvaWQgMCA6IF9jLnRyaW0oKSkgfHwgXCJcIjtcbiAgICAgICAgICAgIGNvbnN0IHNlYXNvbiA9ICgoX2QgPSBwcm9ncmFtRWxlbWVudC5kYXRhc2V0LnNlYXNvbikgPT09IG51bGwgfHwgX2QgPT09IHZvaWQgMCA/IHZvaWQgMCA6IF9kLnRyaW0oKSkgfHwgXCJcIjtcbiAgICAgICAgICAgIGNvbnN0IGFnZXMgPSAoKF9lID0gcHJvZ3JhbUVsZW1lbnQuZGF0YXNldC5hZ2VzKSA9PT0gbnVsbCB8fCBfZSA9PT0gdm9pZCAwID8gdm9pZCAwIDogX2Uuc3BsaXQoXCIsXCIpLm1hcChhZ2UgPT4gYWdlLnRyaW0oKSkpIHx8IFtdO1xuICAgICAgICAgICAgY29uc3QgZ2VuZGVyID0gKChfZiA9IHByb2dyYW1FbGVtZW50LmRhdGFzZXQuZ2VuZGVyKSA9PT0gbnVsbCB8fCBfZiA9PT0gdm9pZCAwID8gdm9pZCAwIDogX2YudHJpbSgpKSB8fCBcIlwiO1xuICAgICAgICAgICAgbGV0IG1hdGNoZXMgPSB0cnVlO1xuICAgICAgICAgICAgaWYgKHNlbGVjdGVkRmlsdGVycy5zdGF0ZSAmJiBzdGF0ZSAhPT0gc2VsZWN0ZWRGaWx0ZXJzLnN0YXRlKVxuICAgICAgICAgICAgICAgIG1hdGNoZXMgPSBmYWxzZTtcbiAgICAgICAgICAgIGlmIChzZWxlY3RlZEZpbHRlcnMuY2l0eSAmJiBjaXR5ICE9PSBzZWxlY3RlZEZpbHRlcnMuY2l0eSlcbiAgICAgICAgICAgICAgICBtYXRjaGVzID0gZmFsc2U7XG4gICAgICAgICAgICBpZiAoc2VsZWN0ZWRGaWx0ZXJzLnByb2dyYW1zICYmIHByb2dyYW1zICE9PSBzZWxlY3RlZEZpbHRlcnMucHJvZ3JhbXMpXG4gICAgICAgICAgICAgICAgbWF0Y2hlcyA9IGZhbHNlO1xuICAgICAgICAgICAgaWYgKHNlbGVjdGVkRmlsdGVycy5zZWFzb24gJiYgc2Vhc29uICE9PSBzZWxlY3RlZEZpbHRlcnMuc2Vhc29uKVxuICAgICAgICAgICAgICAgIG1hdGNoZXMgPSBmYWxzZTtcbiAgICAgICAgICAgIGlmIChzZWxlY3RlZEZpbHRlcnMuYWdlICYmICFhZ2VzLmluY2x1ZGVzKHNlbGVjdGVkRmlsdGVycy5hZ2UpKVxuICAgICAgICAgICAgICAgIG1hdGNoZXMgPSBmYWxzZTtcbiAgICAgICAgICAgIGlmIChzZWxlY3RlZEZpbHRlcnMuZ2VuZGVyICYmIGdlbmRlciAhPT0gc2VsZWN0ZWRGaWx0ZXJzLmdlbmRlcilcbiAgICAgICAgICAgICAgICBtYXRjaGVzID0gZmFsc2U7XG4gICAgICAgICAgICBwcm9ncmFtRWxlbWVudC5zdHlsZS5kaXNwbGF5ID0gbWF0Y2hlcyA/IFwiYmxvY2tcIiA6IFwibm9uZVwiO1xuICAgICAgICB9KTtcbiAgICB9XG59KTtcbi8qKlxuICogQWRqdXN0IHdpZHRoIG9mIHNlbGVjdCBlbGVtZW50IGJhc2VkIG9uIHNlbGVjdGVkIG9wdGlvblxuICovXG5mdW5jdGlvbiBhZGp1c3RTZWxlY3RXaWR0aChzZWxlY3QpIHtcbiAgICBjb25zdCBzZWxlY3RlZE9wdGlvbiA9IHNlbGVjdC5vcHRpb25zW3NlbGVjdC5zZWxlY3RlZEluZGV4XTtcbiAgICBjb25zdCB0ZW1wU3BhbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3NwYW4nKTtcbiAgICB0ZW1wU3Bhbi5zdHlsZS5mb250ID0gd2luZG93LmdldENvbXB1dGVkU3R5bGUoc2VsZWN0KS5mb250O1xuICAgIHRlbXBTcGFuLnN0eWxlLnZpc2liaWxpdHkgPSAnaGlkZGVuJztcbiAgICB0ZW1wU3Bhbi5zdHlsZS5wb3NpdGlvbiA9ICdhYnNvbHV0ZSc7XG4gICAgdGVtcFNwYW4udGV4dENvbnRlbnQgPSBzZWxlY3RlZE9wdGlvbi50ZXh0Q29udGVudDtcbiAgICBkb2N1bWVudC5ib2R5LmFwcGVuZENoaWxkKHRlbXBTcGFuKTtcbiAgICBzZWxlY3Quc3R5bGUud2lkdGggPSB0ZW1wU3Bhbi5vZmZzZXRXaWR0aCAqIDEuMiArICdweCc7XG4gICAgZG9jdW1lbnQuYm9keS5yZW1vdmVDaGlsZCh0ZW1wU3Bhbik7XG59XG5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgKCkgPT4ge1xuICAgIGNvbnN0IHNlbGVjdEVsZW1lbnQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcjZmluZC1wcm9ncmFtLXNlbGVjdCcpO1xuICAgIGNvbnN0IGZpbmRQcm9ncmFtQnV0dG9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignI2ZpbmQtcHJvZ3JhbS1idXR0b24nKTtcbiAgICBpZiAoc2VsZWN0RWxlbWVudCAmJiBmaW5kUHJvZ3JhbUJ1dHRvbikge1xuICAgICAgICBzZWxlY3RFbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsICgpID0+IHtcbiAgICAgICAgICAgIGFkanVzdFNlbGVjdFdpZHRoKHNlbGVjdEVsZW1lbnQpO1xuICAgICAgICAgICAgY29uc3Qgc2VsZWN0ZWRWYWx1ZSA9IHNlbGVjdEVsZW1lbnQudmFsdWUucmVwbGFjZSgvXFxzL2csICctJykudG9Mb3dlckNhc2UoKTtcbiAgICAgICAgICAgIGNvbnN0IGJhc2VVcmwgPSAnL3Byb2dyYW0tc2VhcmNoJztcbiAgICAgICAgICAgIGlmIChzZWxlY3RlZFZhbHVlKSB7XG4gICAgICAgICAgICAgICAgZmluZFByb2dyYW1CdXR0b24uaHJlZiA9IGAke2Jhc2VVcmx9Lz9zdGF0ZT0ke3NlbGVjdGVkVmFsdWV9YDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIGZpbmRQcm9ncmFtQnV0dG9uLmhyZWYgPSBiYXNlVXJsOyAvLyBSZXNldCBpZiBubyB2YWx1ZSBzZWxlY3RlZFxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgYWRqdXN0U2VsZWN0V2lkdGgoc2VsZWN0RWxlbWVudCk7IC8vIEluaXRpYWwgd2lkdGggc2V0LlxuICAgIH1cbn0pO1xuLyoqXG4gKiBUb2dnbGUgZmlsdGVycyBmb3IgcHJvZ3JhbXNcbiAqL1xuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcIkRPTUNvbnRlbnRMb2FkZWRcIiwgKCkgPT4ge1xuICAgIGNvbnN0IHRvZ2dsZUJ1dHRvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwidG9nZ2xlLWZpbHRlcnNcIik7XG4gICAgY29uc3QgZmlsdGVycyA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwiZmlsdGVyc1wiKTtcbiAgICBpZiAodG9nZ2xlQnV0dG9uICYmIGZpbHRlcnMpIHtcbiAgICAgICAgdG9nZ2xlQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoKSA9PiB7XG4gICAgICAgICAgICBmaWx0ZXJzLmNsYXNzTGlzdC50b2dnbGUoXCJvcGVuXCIpO1xuICAgICAgICB9KTtcbiAgICB9XG59KTtcbiJdLCJuYW1lcyI6W10sInNvdXJjZVJvb3QiOiIifQ==