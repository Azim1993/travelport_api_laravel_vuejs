<template>
    <div id="booking" class="section">
		<div class="section-center">
			<div class="container">
				<div class="row" v-if="!listView">
					<div class="col-md-7 col-md-push-5">
						<div class="booking-cta">
							<h1>Make your reservation</h1>
							<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi facere, soluta magnam consectetur molestias itaque
								ad sint fugit architecto incidunt iste culpa perspiciatis possimus voluptates aliquid consequuntur cumque quasi.
								Perspiciatis.
							</p>
						</div>
					</div>
					<div class="col-md-4 col-md-pull-7">
						<booking-form></booking-form>
					</div>
				</div>
                <search-list v-else :searchList="searchList"></search-list>
			</div>
		</div>
	</div>
</template>
<script>
    import bookingForm from "./Form";
    import SearchList from "./SearchList";
    import { Events } from "../../event";
    export default {
        name: 'Home',
        components: {
            bookingForm, SearchList
        },
        data() {
            return {
                searchList: [],
                listView: false
            }
        },
        mounted() {
            Events.$on('searchListEvent', (data) => {
                this.searchList = data;
                this.listView = true;
            })
            Events.$on('backToSearch', () => {
                this.listView = false
            });
        },
        beforeDestroy() {
            Events.$off('searchListEvent');
            Events.$off('backToSearch');
        }
        
    }
</script>

