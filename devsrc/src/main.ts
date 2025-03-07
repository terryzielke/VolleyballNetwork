/*
  GLOBAL FUNCTIONS
*/
function clickOutsideElement(
  element: HTMLElement | null,
  callback: () => void,
  exceptionElement: HTMLElement | null = null
) {
  if (!element) {
    return;
  }
  const outsideClickListener = (event: MouseEvent) => {
    const target = event.target as Node; // Cast to Node for contains()
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
  } else {
    body.classList.remove('scrolling');
  }
  if (scrollTop > lastScrollTop) {
    body.classList.remove('scrolling-up');
  } else {
    body.classList.add('scrolling-up');
  }
  lastScrollTop = scrollTop;
});


/*
  SEARCH FORM ANIMATION
*/
const searchForm = document.querySelector('.search-form') as HTMLElement | null;
const searchField = document.querySelector('.search-field') as HTMLInputElement | null;

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
const menuButton = document.querySelector("#menu-button") as HTMLElement | null;
const primaryMenu = document.querySelector("#primary-menu") as HTMLElement | null;
const bars = document.querySelectorAll("#menu-button .bar");
const subMenus = document.querySelectorAll("#primary-menu .sub-menu");
const menuToggles = document.querySelectorAll('#primary-menu .menu-toggle');

function closeMenu(){
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
    } else {
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
} else {
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
  } else {
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
      const htmlCell = cell as HTMLElement; // Cast to HTMLElement
      htmlCell.style.height = 'auto';
      maxHeight = Math.max(maxHeight, htmlCell.offsetHeight);
    });

    cells.forEach(cell => {
      const htmlCell = cell as HTMLElement; // Cast to HTMLElement
      htmlCell.style.height = `${maxHeight}px`;
    });
  });
}

// Call initially on page load
window.addEventListener('DOMContentLoaded', equalizeCellHeights);

// Call on window resize
let resizeTimeout: ReturnType<typeof setTimeout> | null = null;
window.addEventListener('resize', () => {
  if (resizeTimeout) {
    clearTimeout(resizeTimeout);
  }
  resizeTimeout = setTimeout(equalizeCellHeights, 200);
});



/*
  CONTENT IN VIEWPORT
*/
function isFullyInViewportRobust(element: HTMLElement): boolean {
  const rect = element.getBoundingClientRect();

  return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
  );
}

function handleVisibilityCheck() {
  const elements = document.querySelectorAll(".visible"); // Get all elements with the class "visible"

  elements.forEach((element) => {
      if (element instanceof HTMLElement) { // Type guard to ensure it's an HTMLElement
          if (isFullyInViewportRobust(element)) {
              // Do something, e.g., add a class, change styles, etc.
              element.classList.add("is-visible"); // Example: add a class
          } else {
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
const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      const element = entry.target as HTMLElement; // Type assertion

      if (entry.isIntersecting && entry.intersectionRatio === 1) {
        element.classList.add("is-visible");
      } else {
        element.classList.remove("is-visible");
      }
    });
  },
  { threshold: 1 }
);

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
          const leagueElement = league as HTMLElement;
          leagueElement.style.display = 'block';
        });
      }
      // else, show only the league with the matching leagueId
      else{
        leagueContainers.forEach(league => {
          const leagueElement = league as HTMLElement;
          if (leagueElement.getAttribute('data-league-id') === leagueId) {
            leagueElement.style.display = 'block';
          } else {
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
    const getFilterValue = (id: string): string => {
        const element = document.getElementById(id);
        return element ? (element as HTMLSelectElement).value : "";
    };

    const selectedFilters: Record<string, string> = {
        state: getFilterValue("state"),
        city: getFilterValue("city"),
        programs: getFilterValue("programs"),
        season: getFilterValue("season"),
        age: getFilterValue("age"),
        gender: getFilterValue("gender")
    };

    const programs = document.querySelectorAll(".program");
    
    programs.forEach(program => {
        const programElement = program as HTMLElement;
        const state = programElement.dataset.state?.trim() || "";
        const city = programElement.dataset.city?.trim() || "";
        const programs = programElement.dataset.programs?.trim() || "";
        const season = programElement.dataset.season?.trim() || "";
        const ages = programElement.dataset.ages?.split(",").map(age => age.trim()) || [];
        const gender = programElement.dataset.gender?.trim() || "";
        
        let matches = true;

        if (selectedFilters.state && state !== selectedFilters.state) matches = false;
        if (selectedFilters.city && city !== selectedFilters.city) matches = false;
        if (selectedFilters.programs && programs !== selectedFilters.programs) matches = false;
        if (selectedFilters.season && season !== selectedFilters.season) matches = false;
        if (selectedFilters.age && !ages.includes(selectedFilters.age)) matches = false;
        if (selectedFilters.gender && gender !== selectedFilters.gender) matches = false;

        programElement.style.display = matches ? "block" : "none";
    });
  }
});
