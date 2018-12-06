<template>
    <div class="searchList">
        <h3>Flight List <a href="#" class="btn btn-primary" @click.prevent="backToSearch">Back to Search</a></h3>
        <hr>
        <div class="row">
            <div class="col-sm-4" v-for="(flight,index) in searchList" :key="index">
                <div class="card">
                    <div class="card-body">
                        <p class="text-bold">Flight Number {{ flight.FlightNumber }}</p>
                        <p>Travel Time: {{ flight.TravelTime }}</p>
                        <p>Destination: {{ flight.Destination }}</p>
                        <p>Equipment: {{ flight.Equipment }}</p>
                        <p>Departure Time: {{ flight.DepartureTime }}</p>
                        <p>Carrier: {{ flight.Carrier }}</p>
                        <p>Category: {{ flight.AvailabilityDisplayType }}</p>
                        <a href="#" class="btn btn-primary">Book Flight</a>
                    </div>
                </div>
            </div><br>
        </div>
    </div>
</template>

<script>
    import { Events } from "../../event";
    export default {
        name: 'search-list',
        props: {
            searchList: {
                type: Object,
                required: true
            }
        },
        data() {
            return {

            }
        },
        methods: {
            backToSearch() {
                Events.$emit('backToSearch');
            }
        }

    }
</script>
<style>
.searchList {
    background-color: #fff;
    min-height: 100vh;
    padding: 1rem;
    position: relative;
    z-index: 2;
}
.searchList h3 {
}
</style>


