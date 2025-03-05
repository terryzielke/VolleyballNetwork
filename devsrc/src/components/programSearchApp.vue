<template>
    <!-- filter search bar -->
    <div id="program-filters">
        <div class="container">
            <div class="content">
                <h3>Find a Program</h3>
            </div>
            <div class="content">
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
                <div class="filter">
                    <label for="age">Age</label>
                    <select id="age" v-model="selectedAge">
                        <option value="">All Ages</option>
                        <option v-for="age in availableAges" :key="age" :value="age">{{ age }}</option>
                    </select>
                </div>
                <div class="filter">
                    <label for="gender">Gender</label>
                    <select id="gender" v-model="selectedGender">
                        <option value="">All Genders</option>
                        <option v-for="gender in availableGenders" :key="gender" :value="gender">{{ gender }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- loading and error messages -->
    <div v-if="loading" class="loading">
        <img src="/wp-content/themes/volleyballnetwork/assets/img/UI/Spinner-white.gif" alt="Loading" />
        <span>Loading programs...</span>
    </div>
    <div v-if="error">{{ error }}</div>

    <!-- filtered programs -->
    <div class="container">
        <div class="content">
            <div v-if="filteredPrograms.length">
                <ul id="programs">
                    <li v-for="program in filteredPrograms" :key="program.id" class="program"
                        :city="program.venue_city && program.venue_city.length > 0 ? program.venue_city.map(city => city.name).join(' ') : ''"
                        :state="program.venue_state && program.venue_state.length > 0 ? program.venue_state.map(state => state.name).join(' ') : ''"
                        :country="program.venue_country && program.venue_country.length > 0 ? program.venue_country.map(country => country.name).join(' ') : ''"
                        :age="program.program_age && program.program_age.length > 0 ? program.program_age.map(age => age.name).join(' ') : ''">
                        <div class="program-header">
                            <h3>{{ decodedHTMLSrings(program.venue_title) || 'Venue Not Found' }} - {{ decodedHTMLSrings(program.league_title) || 'League Not Found' }} 
                                <span v-if="program.program_age">- Age {{ program.program_age_range }}</span>
                                <span v-if="program.program_gender" :class="program.program_gender.map(gender => gender.name ).join(' ')"></span>
                            </h3>
                        </div>
                        <div class="program-details">
                            <div class="row">
                                <div class="col col-12 col-md-1">
                                    <div v-if="program.program_season">
                                        <h4 class="orange">{{ program.program_season }}</h4>
                                    </div>
                                    <div v-else>
                                        <p>No matching season found.</p>
                                    </div>
                                </div>
                                <div class="col col-12 col-md-3">
                                    <h4><span v-if="program.venue_city && program.venue_city.length > 0">{{ program.venue_city.map(city => city.name ).join(' ') }}</span><span v-else>City Not Found</span></h4>
                                    <p><span v-if="program.venue_address"><a :href="generateMapUrl(program)" target="_blank">{{ decodedHTML(program.venue_address) }}</a></span><span v-else>Address Not Found</span></p>
                                    <p>
                                        <span v-if="program.venue_city && program.venue_city.length > 0">{{ program.venue_city.map(city => city.name ).join(' ') }}</span>, 
                                        <span v-if="program.venue_state && program.venue_state.length > 0">{{ program.venue_state.map(state => state.name ).join(' ') }}</span>. 
                                        <span v-if="program.venue_postal_code">{{ program.venue_postal_code }}</span>
                                    </p>
                                </div>
                                <div class="col col-12 col-md-3">
                                    <h4>Schedule</h4>
                                    <p v-if="program.program_start_date"><strong>Start Time: </strong>{{ program.program_start_date }}</p>
                                    <p v-else><strong>Start Time: </strong> {{ getSeasonStartDate(program) }}</p>
                                    <p v-if="program.program_end_date"><strong>End Time: </strong>{{ program.program_end_date }}</p>
                                    <p v-else><strong>End Time: </strong> {{ getSeasonEndDate(program) }}</p>
                                    <p v-if="program.program_days">
                                        <strong>Days: </strong>
                                        <span v-if="Array.isArray(program.program_days)">{{ program.program_days.join(', ') }}</span>
                                        <span v-else>{{ program.program_days }}</span>
                                    </p>
                                    <p><strong>Time: </strong><span v-if="program.program_start_time">{{ formatTime(program.program_start_time) }}</span> - <span v-if="program.program_end_time">{{ formatTime(program.program_end_time) }}</span></p>
                                </div>
                                <div class="col col-12 col-md-3">
                                    <h4>Details</h4>
                                    <p v-if="getSeasonPrice(program)">
                                        <strong>Price: </strong>{{ getSeasonPrice(program) }} CAD
                                    </p>
                                    <p v-else-if="program.program_season">
                                        Price not available for {{ program.program_season }}
                                    </p>
                                    <p v-if="program.program_gender"><strong>Gender: </strong> {{ program.program_gender.map(gender => gender.name ).join(' ') }}</p>
                                </div>
                                <!--
                                <div class="col">
                                <h4>Contact</h4>
                                </div>
                                -->
                                <div class="col col-12 col-md-2">
                                    <a v-if="getRegistrationUrl(program)" :href="getRegistrationUrl(program) || undefined" target="_blank" class="button btn">
                                        Register Now
                                    </a>
                                    <p v-else-if="program.program_season">
                                        Registration URL not available for {{ program.program_season }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div v-else-if="!loading && !error">No programs found.</div>
        </div>
    </div>
</template>

<script lang="ts">
    import { defineComponent } from 'vue';
    import axios from 'axios';
    import * as he from 'he';

    const apiUrl = 'http://volleyballnetwork.local/wp-json/wp/v2'; // Your WordPress API base URL

    interface Program {  // Define an interface for your Program type
        id: number;
        title: { rendered: string };
        program_league?: number | null;
        program_season?: string | null;
        program_venue?: number | null;
        program_age?: { id: number; name: string; slug: string; }[] | null;
        program_age_range?: string | null;
        program_gender?: { id: string; name: string; slug: string; }[] | null;
        program_start_date?: string | null;
        program_end_date?: string | null;
        program_days?: string | null;
        program_time?: string | null;
        program_start_time?: string | null;
        program_end_time?: string | null;
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
        venue_city?: { ID: number; name: string; SLUG: string; TAXONOMY: string; DESCRIPTION: string; PARENT: number; COUNT: number; LINK: string; META: any[]; YOAST_HEAD: string;  }[] | null;
        venue_state?: { ID: number; name: string; SLUG: string; TAXONOMY: string; DESCRIPTION: string; PARENT: number; COUNT: number; LINK: string; META: any[]; YOAST_HEAD: string;  }[] | null;
        venue_country?: { ID: number; name: string; SLUG: string; TAXONOMY: string; DESCRIPTION: string; PARENT: number; COUNT: number; LINK: string; META: any[]; YOAST_HEAD: string;  }[] | null;
    }

    export default defineComponent({
        name: 'ProgramSearchApp',
        computed: {
            filteredPrograms(): Program[] {
                return this.programs.filter(program => {
                    const stateMatch = !this.selectedState || (program.venue_state && program.venue_state.length > 0 && program.venue_state.some(state => state.name.toLowerCase() === this.selectedState.toLowerCase()));

                    const cityMatch = !this.selectedCity || (program.venue_city && program.venue_city.length > 0 && program.venue_city.some(city => city.name.toLowerCase() === this.selectedCity.toLowerCase()));

                    const ageMatch = !this.selectedAge || (program.program_age && program.program_age.length > 0 && program.program_age.some(age => age.name.toLowerCase() === this.selectedAge.toLowerCase()));

                    const genderMatch = !this.selectedGender || (program.program_gender && program.program_gender.length > 0 && program.program_gender.some(gender => gender.name.toLowerCase() === this.selectedGender.toLowerCase()));

                    return stateMatch && cityMatch && ageMatch && genderMatch;
                });
            },
            registrationUrl(): string | null {
                return this.program ? this.getRegistrationUrl(this.program) : null;
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
                availableCities: [] as string[],
                selectedAge: '',
                availableAges: [] as string[],
                selectedGender: '',
                availableGenders: [] as string[],
                program: null as Program | null,
            };
        },
        mounted() {
            this.fetchPrograms();
        },
        methods: {
            decodedHTML(value: string | null | undefined): string {
                if (value) {
                    return he.decode(value); // Use he.decode() to decode HTML entities
                }
                return '';
            },
            decodedHTMLSrings(title: string | null | undefined): string {  // Make title parameter accept null or undefined
                if (title) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = title;
                    return tempDiv.textContent || tempDiv.innerText || '';
                }
                return '';
            },
            getRegistrationUrl(program: Program): string | null {
                if (!program.program_season) {
                    return null;
                }
                switch (program.program_season) {
                    case 'winter':
                        return program.season_winter_registration || null;
                    case 'spring':
                        return program.season_spring_registration || null;
                    case 'summer':
                        return program.season_summer_registration || null;
                    case 'fall':
                        return program.season_fall_registration || null;
                    default:
                        return null;
                }
            },
            getSeasonPrice(program: Program): string | null {
                if (!program.program_season) {
                    return null;
                }
                switch (program.program_season) {
                    case 'winter':
                        return program.season_winter_price || null;
                    case 'spring':
                        return program.season_spring_price || null;
                    case 'summer':
                        return program.season_summer_price || null;
                    case 'fall':
                        return program.season_fall_price || null;
                    default:
                        return null;
                }
            },
            getSeasonStartDate(program: Program): string | null {
                if (!program.program_season) {
                    return null;
                }
                switch (program.program_season) {
                    case 'winter':
                        return program.season_winter_start_date || null;
                    case 'spring':
                        return program.season_spring_start_date || null;
                    case 'summer':
                        return program.season_summer_start_date || null;
                    case 'fall':
                        return program.season_fall_start_date || null;
                    default:
                        return null;
                }
            },
            getSeasonEndDate(program: Program): string | null {
                if (!program.program_season) {
                    return null;
                }
                switch (program.program_season) {
                    case 'winter':
                        return program.season_winter_end_date || null;
                    case 'spring':
                        return program.season_spring_end_date || null;
                    case 'summer':
                        return program.season_summer_end_date || null;
                    case 'fall':
                        return program.season_fall_end_date || null;
                    default:
                        return null;
                }
            },
            formatTime(time: string | null | undefined): string {
                if (!time) return '';

                const [hours, minutes] = (time.split(':')).map(Number);
                let period = hours >= 12 ? 'PM' : 'AM';
                let formattedHours = hours % 12;
                formattedHours = formattedHours === 0 ? 12 : formattedHours;

                return `${formattedHours}:${minutes.toString().padStart(2, '0')} ${period}`;
            },
            generateMapUrl(program: Program): string {
                let address = `${program.venue_address || ''}, ${program.venue_city && program.venue_city.length > 0 ? program.venue_city.map(city => city.name).join(' ') : ''}, ${program.venue_state && program.venue_state.length > 0 ? program.venue_state.map(state => state.name).join(' ') : ''} ${program.venue_postal_code || ''}`;
                address = he.decode(address);
                address = encodeURIComponent(address.trim());
                return `https://www.google.com/maps/search/?api=1&query=${address}`;
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
                    const response = await axios.get(`${apiUrl}/program?per_page=100`);

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
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.local_league_league = leagueResponse.data.local_league_league;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.local_league_league = null; // Or some default value
                                }
                                try {
                                    if (program.local_league_league) {
                                        console.log("Fetching league:", `${apiUrl}/league/${program.local_league_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/league/${program.local_league_league}`);
                                        program.league_title = leagueResponse.data.title.rendered;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.league_title = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.local_league_league = leagueResponse.data.local_league_league;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.local_league_league = null; // Or some default value
                                }
                                // seasons
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_winter_registration = leagueResponse.data.season_winter_registration;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_winter_registration = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_winter_price = leagueResponse.data.season_winter_price;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_winter_price = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_winter_start_date = leagueResponse.data.season_winter_start_date;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_winter_start_date = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_winter_end_date = leagueResponse.data.season_winter_end_date;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_winter_end_date = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_spring_registration = leagueResponse.data.season_spring_registration;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_spring_registration = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_spring_price = leagueResponse.data.season_spring_price;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_spring_price = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_spring_start_date = leagueResponse.data.season_spring_start_date;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_spring_start_date = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_spring_end_date = leagueResponse.data.season_spring_end_date;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_spring_end_date = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_summer_registration = leagueResponse.data.season_summer_registration;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_summer_registration = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_summer_price = leagueResponse.data.season_summer_price;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_summer_price = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_summer_start_date = leagueResponse.data.season_summer_start_date;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_summer_start_date = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_summer_end_date = leagueResponse.data.season_summer_end_date;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_summer_end_date = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_fall_registration = leagueResponse.data.season_fall_registration;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_fall_registration = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_fall_price = leagueResponse.data.season_fall_price;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_fall_price = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_fall_start_date = leagueResponse.data.season_fall_start_date;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_fall_start_date = null; // Or some default value
                                }
                                try {
                                    if (program.program_league) {
                                        console.log("Fetching league:", `${apiUrl}/local-league/${program.program_league}`); // Log the URL
                                        const leagueResponse = await axios.get(`${apiUrl}/local-league/${program.program_league}`);
                                        program.season_fall_end_date = leagueResponse.data.season_fall_end_date;
                                    }
                                } catch (leagueError) {
                                    console.error("Error fetching league:", leagueError); // Log the specific error
                                    program.season_fall_end_date = null; // Or some default value
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
                                // season
                                // start time
                                try {
                                    if (program.id) {
                                        const metaResponse = await axios.get(`${apiUrl}/program/${program.id}`);
                                        program.program_season = metaResponse.data.program_season;
                                    }
                                } catch (programMetaError) {
                                    console.error("Error fetching league season:", programMetaError);
                                    program.program_season = null;
                                }
                                try {
                                    if (program.id) {
                                        const metaResponse = await axios.get(`${apiUrl}/program/${program.id}`);
                                        program.program_start_date = metaResponse.data.program_start_date;
                                    }
                                } catch (programMetaError) {
                                    console.error("Error fetching league season:", programMetaError);
                                    program.program_start_date = null;
                                }
                                try {
                                    if (program.id) {
                                        const metaResponse = await axios.get(`${apiUrl}/program/${program.id}`);
                                        program.program_start_time = metaResponse.data.program_start_time;
                                    }
                                } catch (programMetaError) {
                                    console.error("Error fetching league season:", programMetaError);
                                    program.program_start_time = null;
                                }
                                try {
                                    if (program.id) {
                                        const metaResponse = await axios.get(`${apiUrl}/program/${program.id}`);
                                        program.program_end_time = metaResponse.data.program_end_time;
                                    }
                                } catch (programMetaError) {
                                    console.error("Error fetching league season:", programMetaError);
                                    program.program_end_time = null;
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
                        After fetching programs, populate filter options
                    */
                    if (this.programs && this.programs.length > 0) {
                        const uniqueStates = new Set<string>();
                        const uniqueCities = new Set<string>();
                        const uniqueAges = new Set<string>();
                        const uniqueGenders = new Set<string>();

                        this.programs.forEach(program => {
                            if (program.venue_state && program.venue_state.length > 0) {
                                program.venue_state.forEach(state => uniqueStates.add(state.name));
                            }
                            if (program.venue_city && program.venue_city.length > 0) {
                                program.venue_city.forEach(city => uniqueCities.add(city.name));
                            }
                            if (program.program_age && program.program_age.length > 0) {
                                program.program_age.forEach(age => uniqueAges.add(age.name));
                            }
                            if (program.program_gender && program.program_gender.length > 0) {
                                program.program_gender.forEach(gender => uniqueGenders.add(gender.name));
                            }
                        });

                        this.availableStates = Array.from(uniqueStates).sort();
                        this.availableCities = Array.from(uniqueCities).sort();
                        this.availableAges = Array.from(uniqueAges).sort();
                        this.availableGenders = Array.from(uniqueGenders).sort();
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
    }
</style>