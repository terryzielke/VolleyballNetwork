interface Program {
    id: number;
    title: {
        rendered: string;
    };
    program_league?: number | null;
    program_season?: string | null;
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
    local_league_league?: number | null;
    season_winter_registration?: string | null;
    season_winter_price?: string | null;
    season_winter_start_date?: string | null;
    season_winter_end_date?: string | null;
    season_spring_registration?: string | null;
    season_spring_price?: string | null;
    season_spring_start_date?: string | null;
    season_spring_end_date?: string | null;
    season_summer_registration?: string | null;
    season_summer_price?: string | null;
    season_summer_start_date?: string | null;
    season_summer_end_date?: string | null;
    season_fall_registration?: string | null;
    season_fall_price?: string | null;
    season_fall_start_date?: string | null;
    season_fall_end_date?: string | null;
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
    }[] | null;
}
declare const _default: import("vue").DefineComponent<{}, {}, {
    programs: Program[];
    loading: boolean;
    error: string | null;
    selectedState: string;
    availableStates: string[];
    selectedCity: string;
    availableCities: string[];
    selectedAge: string;
    availableAges: string[];
    selectedGender: string;
    availableGenders: string[];
    program: Program | null;
}, {
    filteredPrograms(): Program[];
    registrationUrl(): string | null;
}, {
    decodedHTML(value: string | null | undefined): string;
    decodedHTMLSrings(title: string | null | undefined): string;
    getRegistrationUrl(program: Program): string | null;
    getSeasonPrice(program: Program): string | null;
    getSeasonStartDate(program: Program): string | null;
    getSeasonEndDate(program: Program): string | null;
    fetchPrograms(): Promise<void>;
}, import("vue").ComponentOptionsMixin, import("vue").ComponentOptionsMixin, {}, string, import("vue").PublicProps, Readonly<{}> & Readonly<{}>, {}, {}, {}, {}, string, import("vue").ComponentProvideOptions, true, {}, any>;
export default _default;
