<template>
    <div class="booking-form">
        <form @submit.prevent="checkFlight">
            <div class="form-group">
                <span class="form-label">Your Origin</span>
                <multiselect v-model="flight.origin"
                    label="name"
                    track-by="code"
                    placeholder="Type to search"
                    :options="airports"
                    :multiple="false"
                    :searchable="true"
                    :loading="isLoading"
                    :internal-search="false"
                    :show-no-results="true"
                    :hide-selected="false"
                    @search-change="fetchAriports">
                    <template slot="tag" slot-scope="{ option, remove }"><span class="custom__tag"><span>{{ option.name }}</span><span class="custom__remove" @click="remove(option)">❌</span></span></template>
                    <template slot="clear" slot-scope="props">
                    <div class="multiselect__clear" v-if="airports.length" @mousedown.prevent.stop="clearAll(props.search)"></div>
                    </template><span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
                </multiselect>
            </div>
            <div class="form-group">
                <span class="form-label">Your Destination</span>
                <multiselect v-model="flight.destination"
                    label="name"
                    track-by="code"
                    placeholder="Type to search"
                    :options="airports"
                    :multiple="false"
                    :searchable="true"
                    :loading="isLoading"
                    :internal-search="false"
                    :show-no-results="true"
                    :hide-selected="false"
                    @search-change="fetchAriports">
                    <template slot="tag" slot-scope="{ option, remove }"><span class="custom__tag"><span>{{ option.name }}</span><span class="custom__remove" @click="remove(option)">❌</span></span></template>
                    <template slot="clear" slot-scope="props">
                    <div class="multiselect__clear" v-if="airports.length" @mousedown.prevent.stop="clearAll(props.search)"></div>
                    </template><span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
                </multiselect>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <span class="form-label">Check In</span>
                        <input v-model="flight.start" class="form-control" type="date" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <span class="form-label">Return (optional)</span>
                        <input v-model="flight.return" class="form-control" type="date">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <span class="form-label">Seats</span>
                        <select v-model="flight.seats" class="form-control" required>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                        </select>
                        <span class="select-arrow"></span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <span class="form-label">Children (optional)</span>
                        <select v-model="flight.child" class="form-control">
                            <option>0</option>
                            <option>1</option>
                            <option>2</option>
                        </select>
                        <span class="select-arrow"></span>
                    </div>
                </div>
            </div>
            <div class="form-btn">
                <button type="submit"
                    :disabled="searchLoad"
                    class="submit-btn">Check availability</button>
            </div>
        </form>
    </div>
</template>
<script>
    import axios from "axios";
    import Multiselect from 'vue-multiselect'
    import { Events } from "../../event";
    export default {
        name: 'booking-form',
        components: {
            Multiselect
        },
        data() {
            return {
                flight: {
                    origin: '',
                    destination: '',
                    start: '',
                    return: '',
                    seats: '',
                    child: '',
                },
                airports: [],
                isLoading: false,
                searchLoad: false,
            }
        },
        mounted() {
            // this.fetchAriports();
        },
        methods: {
            fetchAriports (query) {
                isLoading: true
                axios.get('/api/airports', {params: {origin: query}})
                    .then( response => {
                        this.airports = response.data
                        isLoading: false
                    })
            },
            checkFlight () {
                this.searchLoad = true;
                axios.post('/api/flight_check', this.flight)
                    .then( response => {
                        console.log(response);
                        let promise = new Promise(function(resolve, reject) {
                            resolve(response);
                        });
                        promise.then((response)=> {
                            Events.$emit('searchListEvent', response.data)
                        })
                    })
            } 
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
