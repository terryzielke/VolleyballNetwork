interface Program {
    id: number;
    title: {
        rendered: string;
    };
    program_league?: number | null;
    program_league_season?: number | null;
    program_venue?: number | null;
    program_age?: {
        id: number;
        name: string;
        slug: string;
    }[] | null;
    program_age_range?: string | null;
    program_gender?: {
        id: string;
        name: string;
        slug: string;
    }[] | null;
    program_start_date?: string | null;
    program_end_date?: string | null;
    program_days?: string | null;
    program_time?: string | null;
    league_title?: string | null;
    seasons?: {
        id: number;
        season: string;
        year: string;
        registration_url: string;
        price: string;
    }[] | null;
    venue_title?: string | null;
    venue_address?: string | null;
    venue_postal_code?: string | null;
    venue_city?: {
        ID: number;
        name: string;
        SLUG: string;
        TAXONOMY: string;
        DESCRIPTION: string;
        PARENT: number;
        COUNT: number;
        LINK: string;
        META: any[];
        YOAST_HEAD: string;
        YOAST_HEAD_JSON: any;
        _LINKS: any;
    }[] | null;
    venue_state?: {
        ID: number;
        name: string;
        SLUG: string;
        TAXONOMY: string;
        DESCRIPTION: string;
        PARENT: number;
        COUNT: number;
        LINK: string;
        META: any[];
        YOAST_HEAD: string;
        YOAST_HEAD_JSON: any;
        _LINKS: any;
    }[] | null;
    venue_country?: {
        ID: number;
        name: string;
        SLUG: string;
        TAXONOMY: string;
        DESCRIPTION: string;
        PARENT: number;
        COUNT: number;
        LINK: string;
        META: any[];
        YOAST_HEAD: string;
        YOAST_HEAD_JSON: any;
        _LINKS: any;
    }[] | null;
    selectedState?: string;
}
declare const _default: import("vue").DefineComponent<{}, {}, {
    programs: Program[];
    loading: boolean;
    error: string | null;
    selectedState: string;
    availableStates: string[];
    selectedCity: string;
    availableCities: string[];
}, {
    matchingSeason(): (program: Program) => {
        id: number;
        season: string;
        year: string;
        registration_url: string;
        price: string;
    } | null;
    filteredPrograms(): Program[];
}, {
    decodedHTMLSrings(title: string | null | undefined): string;
    fetchPrograms(): Promise<void>;
}, import("vue").ComponentOptionsMixin, import("vue").ComponentOptionsMixin, {}, string, import("vue").PublicProps, Readonly<{}> & Readonly<{}>, {}, {}, {}, {}, string, import("vue").ComponentProvideOptions, true, {}, any>;
export default _default;
