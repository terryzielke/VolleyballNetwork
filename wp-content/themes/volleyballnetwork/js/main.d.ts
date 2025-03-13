declare function clickOutsideElement(element: HTMLElement | null, callback: () => void, exceptionElement?: HTMLElement | null): (() => void) | undefined;
/**
 * If page is not at top, add scrolling class to body
 * If page is scrolling up, add scrolling-up class to body
 */
declare let lastScrollTop: number;
declare const searchForm: HTMLElement | null;
declare const searchField: HTMLInputElement | null;
declare const menuButton: HTMLElement | null;
declare const primaryMenu: HTMLElement | null;
declare const bars: NodeListOf<Element>;
declare const subMenus: NodeListOf<Element>;
declare const menuToggles: NodeListOf<Element>;
declare function closeMenu(): void;
declare const menuItems: NodeListOf<Element>;
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
 * Adjust width of select element based on selected option
 */
declare function adjustSelectWidth(select: HTMLSelectElement): void;
