<template>
    <div v-if="loading">Loading programs...</div>
    <div v-if="error">{{ error }}</div>

    <!-- filter search bar -->
    <div id="program-filters">
        <div class="filter">
            <label for="state">State</label>
            <select id="state" v-model="selectedState">
                <option value="">All Provinces</option>
                <option v-for="state in availableStates" :key="state" :value="state">{{ state }}</option>
            </select>
        </div>
        <div class="filter">
            <label for="city">City</label>
            <select id="city" v-model="selectedCity">
                <option value="">All Cities</option>
                <option v-for="city in availableCities" :key="city" :value="city">{{ city }}</option>
            </select>
        </div>
    </div>

    <!-- filtered programs -->
    <div v-if="filteredPrograms.length">
        <ul>
            <li v-for="program in filteredPrograms" :key="program.id" class="program"
                :city="program.venue_city && program.venue_city.length > 0 ? program.venue_city.map(city => city.name).join(' ') : ''"
                :state="program.venue_state && program.venue_state.length > 0 ? program.venue_state.map(state => state.name).join(' ') : ''"
                :country="program.venue_country && program.venue_country.length > 0 ? program.venue_country.map(country => country.name).join(' ') : ''"
                :age="program.program_age && program.program_age.length > 0 ? program.program_age.map(age => age.name).join(' ') : ''">
                <div class="program-header">
                    <h3>{{ decodedHTMLSrings(program.venue_title) || 'Venue Not Found' }} - {{ decodedHTMLSrings(program.league_title) || 'League Not Found' }} 
                        <span v-if="program.program_age">- Age {{ program.program_age_range }}</span>
                    </h3>
                </div>
                <div class="program-details">
                    <div class="row">
                        <div class="col">
                            <div v-if="matchingSeason(program)">  
                                <h4 v-if="matchingSeason(program)" class="orange">{{ matchingSeason(program)?.season }} {{ matchingSeason(program)?.year }}</h4>
                            </div>
                            <div v-else>
                                <p>No matching season found.</p>
                            </div>
                        </div>
                        <div class="col">
                            <h4><span v-if="program.venue_city && program.venue_city.length > 0">{{ program.venue_city.map(city => city.name ).join(' ') }}</span><span v-else>City Not Found</span></h4>
                            <p><span v-if="program.venue_address">{{ program.venue_address }}</span><span v-else>Address Not Found</span></p>
                            <p>
                                <span v-if="program.venue_city && program.venue_city.length > 0">{{ program.venue_city.map(city => city.name ).join(' ') }}</span>, 
                                <span v-if="program.venue_state && program.venue_state.length > 0">{{ program.venue_state.map(state => state.name ).join(' ') }}</span>. 
                                <span v-if="program.venue_postal_code">{{ program.venue_postal_code }}</span>
                            </p>
                        </div>
                        <div class="col">
                            <h4>Schedule</h4>
                            <p v-if="program.program_start_date"><strong>Start Time: </strong>{{ program.program_start_date }}</p>
                            <p v-if="program.program_end_date"><strong>End Time: </strong>{{ program.program_end_date }}</p>
                            <p v-if="program.program_days">
                                <strong>Days: </strong>
                                <span v-if="Array.isArray(program.program_days)">{{ program.program_days.join(', ') }}</span>
                                <span v-else>{{ program.program_days }}</span>
                            </p>
                            <p v-if="program.program_time"><strong>Time: </strong>{{ program.program_time }}</p>
                        </div>
                        <div class="col">
                            <h4>Details</h4>
                            <p v-if="matchingSeason(program)"><strong>Price: </strong>{{ matchingSeason(program)?.price }}</p>
                            <p v-if="program.program_gender"><strong>Gender: </strong> {{ program.program_gender.map(gender => gender.name ).join(' ') }}</p>
                        </div>
                        <!--
                        <div class="col">
                        <h4>Contact</h4>
                        </div>
                        -->
                        <div class="col">
                            <span v-if="matchingSeason(program)"><a :href="matchingSeason(program)?.registration_url" class="btn" target="_blank">Register Now</a></span>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div v-else-if="!loading && !error">No programs found.</div>
</template>

<script lang="ts">
    import { defineComponent } from 'vue';
    import axios from 'axios';

    const apiUrl = 'http://volleyballnetwork.local/wp-json/wp/v2'; // Your WordPress API base URL

    interface Program {  // Define an interface for your Program type
        id: number;
        title: { rendered: string };
        program_league?: number | null;
        program_league_season?: number | null;
        program_venue?: number | null;
        program_age?: { id: number; name: string; slug: string; }[] | null;
        program_age_range?: string | null;
        program_gender?: { id: string; name: string; slug: string; }[] | null;
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
        venue_city?: { ID: number; name: string; SLUG: string; TAXONOMY: string; DESCRIPTION: string; PARENT: number; COUNT: number; LINK: string; META: any[]; YOAST_HEAD: string; YOAST_HEAD_JSON: any; _LINKS: any; }[] | null;
        venue_state?: { ID: number; name: string; SLUG: string; TAXONOMY: string; DESCRIPTION: string; PARENT: number; COUNT: number; LINK: string; META: any[]; YOAST_HEAD: string; YOAST_HEAD_JSON: any; _LINKS: any; }[] | null;
        venue_country?: { ID: number; name: string; SLUG: string; TAXONOMY: string; DESCRIPTION: string; PARENT: number; COUNT: number; LINK: string; META: any[]; YOAST_HEAD: string; YOAST_HEAD_JSON: any; _LINKS: any; }[] | null;
        selectedState?: string;
    }

    export default defineComponent({
        name: 'ProgramSearchApp',
        computed: {
            matchingSeason(): (program: Program) => { id: number; season: string; year: string; registration_url: string; price: string; } | null {
                return (program: Program) => {
                    if (program.program_league_season && program.seasons) {
                        return program.seasons.find(season => season.id === program.program_league_season) || null;
                    }
                    return null;
                };
            },
            filteredPrograms(): Program[] {
                return this.programs.filter(program => {
                    const stateMatch = !this.selectedState || (program.venue_state && program.venue_state.length > 0 && program.venue_state.some(state => state.name.toLowerCase() === this.selectedState.toLowerCase()));

                    const cityMatch = !this.selectedCity || (program.venue_city && program.venue_city.length > 0 && program.venue_city.some(city => city.name.toLowerCase() === this.selectedCity.toLowerCase()));

                    return stateMatch && cityMatch;
                });
            }
        },
        data() {
            return {
                programs: [] as Program[],
                loading: true,
                error: null as string | null,
                selectedState: '',
                availableStates: [] as string[],
                selectedCity: '',
                availableCities: [] as string[]
            };
        },
        mounted() {
            this.fetchPrograms();
        },
        methods: {
            decodedHTMLSrings(title: string | null | undefined): string {  // Make title parameter accept null or undefined
                if (title) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = title;
                    return tempDiv.textContent || tempDiv.innerText || '';
                }
                return '';
            },
            async fetchPrograms() {
                this.loading = true;
                this.error = null;
                if (this.programs && this.programs.length > 0) { // After fetching programs, populate availableStates
                    const uniqueStates = new Set<string>();
                    this.programs.forEach(program => {
                        if (program.venue_state && program.venue_state.length > 0) {
                            program.venue_state.forEach(state => uniqueStates.add(state.name));
                        }
                    });
                    this.availableStates = Array.from(uniqueStates).sort(); // Sort states alphabetically
                }

                try {
                    const response = await axios.get(`${apiUrl}/program`);

                    if (Array.isArray(response.data)) {
                    const programsWithLeaguesAndVenues = await Promise.all(
                        response.data.map(async (programData: unknown) => {
                        if (typeof programData === 'object' && programData !== null) {
                            try {
                                const program = programData as Program;

                                /*
                                    LEAGUE:
                                */
                            // title
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/league/${program.program_league}`);
                                        program.league_title = leagueResponse.data.title.rendered;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.league_title = null; // Or some default value
                                }
                                // seasons
                                try {
                                    if (program.program_league) { // Fetch seasons if program ID exists
                                        const leagueResponse = await axios.get(`${apiUrl}/league/${program.program_league}`);
                                        program.seasons = leagueResponse.data.seasons;
                                    }
                                } catch (seasonsError) {
                                    console.error("Error fetching seasons:", seasonsError);
                                    program.seasons = null;
                                }
                                /*
                                    VENUE:
                                */
                            // title
                                try {
                                    if (program.program_venue) {
                                        console.log("Fetching venue:", `${apiUrl}/venue/${program.program_venue}`); // Log the URL
                                        const venueResponse = await axios.get(`${apiUrl}/venue/${program.program_venue}`);
                                        program.venue_title = venueResponse.data.title.rendered;
                                    }
                                } catch (venueError) {
                                    console.error("Error fetching venue:", venueError);
                                    program.venue_title = null;
                                }
                                // address
                                try {
                                    if (program.program_venue) {
                                        console.log("Fetching venue:", `${apiUrl}/venue/${program.program_venue}`); // Log the URL
                                        const venueResponse = await axios.get(`${apiUrl}/venue/${program.program_venue}`);
                                        program.venue_address = venueResponse.data.venue_address;
                                    }
                                } catch (venueError) {
                                    console.error("Error fetching venue:", venueError);
                                    program.venue_address = null;
                                }
                                // postal code
                                try {
                                    if (program.program_venue) {
                                        console.log("Fetching venue:", `${apiUrl}/venue/${program.program_venue}`); // Log the URL
                                        const venueResponse = await axios.get(`${apiUrl}/venue/${program.program_venue}`);
                                        program.venue_postal_code = venueResponse.data.venue_postal_code;
                                    }
                                } catch (venueError) {
                                    console.error("Error fetching venue:", venueError);
                                    program.venue_postal_code = null;
                                }
                                // taxonomies
                                try {
                                    if (program.program_venue) {
                                    const cityTermsResponse = await axios.get(`${apiUrl}/city?post=${program.program_venue}`);
                                    program.venue_city = cityTermsResponse.data;
                                    }
                                } catch (cityTermsError) {
                                    console.error("Error fetching city terms:", cityTermsError);
                                    program.venue_city = null;
                                }
                                try {
                                    if (program.program_venue) {
                                    const stateTermsResponse = await axios.get(`${apiUrl}/state?post=${program.program_venue}`);
                                    program.venue_state = stateTermsResponse.data;
                                    }
                                } catch (stateTermsError) {
                                    console.error("Error fetching state terms:", stateTermsError);
                                    program.venue_state = null;
                                }
                                try {
                                    if (program.program_venue) {
                                    const countryTermsResponse = await axios.get(`${apiUrl}/country?post=${program.program_venue}`);
                                    program.venue_country = countryTermsResponse.data;
                                    }
                                } catch (countryTermsError) {
                                    console.error("Error fetching country terms:", countryTermsError);
                                    program.venue_country = null;
                                }
                                /*
                                    PROGRAM:
                                */
                                // league season
                                try {
                                    if (program.id) {
                                        const metaResponse = await axios.get(`${apiUrl}/program/${program.id}`);
                                        program.program_league_season = metaResponse.data.program_league_season;
                                    }
                                } catch (leagueSeasonError) {
                                    console.error("Error fetching league season:", leagueSeasonError);
                                    program.program_league_season = null;
                                }
                                // start time
                                try {
                                    if (program.id) {
                                        const metaResponse = await axios.get(`${apiUrl}/program/${program.id}`);
                                        program.program_start_date = metaResponse.data.program_start_date;
                                    }
                                } catch (programMetaError) {
                                    console.error("Error fetching league season:", programMetaError);
                                    program.program_start_date = null;
                                }
                                // taxonomies
                                try {
                                    if (program.id) {
                                        const termsResponse = await axios.get(`${apiUrl}/age?post=${program.id}`);
                                        program.program_age = termsResponse.data; // Assign the array of terms
                                        // smallest value in array
                                        const minAge = program.program_age && program.program_age.length > 0 
                                            ? Math.min(...program.program_age.map(o => parseInt(o.name, 10)).filter(age => !isNaN(age))) 
                                            : null;
                                        // largest value in array
                                        const maxAge = program.program_age && program.program_age.length > 0 
                                            ? Math.max(...program.program_age.map(o => parseInt(o.name, 10)).filter(age => !isNaN(age))) 
                                            : null;
                                        program.program_age_range = minAge && maxAge ? `${minAge} - ${maxAge}` : null;
                                    }
                                } catch (ageGroupError) {
                                    console.error("Error fetching age groups:", ageGroupError);
                                    program.program_age = null;
                                }
                                try {
                                    if (program.id) {
                                        const termsResponse = await axios.get(`${apiUrl}/gender?post=${program.id}`);
                                        program.program_gender = termsResponse.data; // Assign the array of terms
                                    }
                                } catch (genderGroupError) {
                                    console.error("Error fetching gender groups:", genderGroupError);
                                    program.program_gender = null;
                                }

                                return program;
                            } catch (e) {
                                console.error("Error processing program:", programData, e);
                                this.error = "Error processing program data. Check console for details";
                                return null;
                            }
                        } else {
                            console.error("Invalid program data:", programData);
                            this.error = "Invalid program data in the response. Check the console for details.";
                            return null;
                        }
                        })
                    );
                    this.programs = programsWithLeaguesAndVenues.filter(program => program !== null) as Program[];

                    /*
                        After fetching programs, populate availableStates and availableCities
                    */
                    if (this.programs && this.programs.length > 0) {
                        const uniqueStates = new Set<string>();
                        const uniqueCities = new Set<string>();

                        this.programs.forEach(program => {
                            if (program.venue_state && program.venue_state.length > 0) {
                                program.venue_state.forEach(state => uniqueStates.add(state.name));
                            }
                            if (program.venue_city && program.venue_city.length > 0) {
                                program.venue_city.forEach(city => uniqueCities.add(city.name));
                            }
                        });

                        this.availableStates = Array.from(uniqueStates).sort();
                        this.availableCities = Array.from(uniqueCities).sort();
                    }

                } else {
                    console.error("Invalid API response:", response.data);
                    this.error = "Invalid API response. Check the console for details.";
                }
            } catch (err) {
                console.error("API request error:", err);
                this.error = "Error fetching programs. Please try again later.";
            } finally {
                this.loading = false; 
            }
            }
        }
    });
</script>

<style scoped>
    /* Component-specific styles */
    h2 {
        color: blue;
    }
</style>