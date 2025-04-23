declare function clickOutsideElement(element: HTMLElement | null, callback: () => void, exceptionElement?: HTMLElement | null): (() => void) | undefined;
/**
 * If page is not at top, add scrolling class to body
 * If page is scrolling up, add scrolling-up class to body
 *
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
declare const searchForm: HTMLElement | null;
declare const searchField: HTMLInputElement | null;
declare function equalizeCellHeights(): void;
declare let resizeTimeout: ReturnType<typeof setTimeout> | null;
declare function isFullyInViewportRobust(element: HTMLElement): boolean;
declare function handleVisibilityCheck(): void;
declare const observer: IntersectionObserver;
declare const visibleElements: NodeListOf<Element>;
/**
 * On click of #league-filters .league-filter
 * get value from data-league-id attribute
 * Find #league-container .league with matching data-league-id
 * Hide all other .league elements
 * Show the matching .league element
 */
declare const leagueFilters: NodeListOf<Element>;
declare const leagueContainers: NodeListOf<Element>;
/**
 * Search page results filtering
 */
declare const resultFilters: NodeListOf<Element>;
declare const resultListings: NodeListOf<Element>;
/**
 * Adjust width of select element based on selected option
 */
declare function adjustSelectWidth(select: HTMLSelectElement): void;
