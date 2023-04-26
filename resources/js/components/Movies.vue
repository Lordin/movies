<template>

	<div>

    <div class="row ">
        <div class="col-lg-6 py-3">
            <h4>Movies</h4>
        </div>
        <div class="col-lg-6 text-end py-3">
            <button v-if="currentUser" class="btn btn-success" type="button" @click="addMovie">Add a movie</button>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-6 py-3">
            <span>Found {{movies.length}} movies</span>
        </div>
        <div class="col-lg-6 text-end py-3">
            <label>Sort by</label>
            <select v-model="sortBy" @change="getData">
                <option value="">-</option>
                <option value="likes">Likes</option>
                <option value="hates">Hates</option>
                <option value="date">Dates</option>
            </select>
        </div>
    </div>

    <div v-for="(movie, index) in movies" class="row py-2">
    	<div class="col-12">
        <div class="card">
            <div class="card-header mx-0">
                <div class="row">
                    <div class="col-lg-6">
                        <h5>{{movie.title}}</h5>
                    </div>
                    <div class="col-lg-6 text-end">
                        <span>Posted {{dateFix(movie.created_at)}}</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <span>{{movie.description}}</span>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-4">
                        <span>{{movie.likes_count}}</span>
                        <span>|</span>
                        <span>{{movie.hates_count}}</span>
                    </div>
                    <div class="col-lg-4 text-center">
                    	<div v-if="currentUser && currentUser.id !== movie.user_id">
                        <button class="btn btn-sm" :class="movie.buttonStatus == 'like' ? 'btn-success' : 'btn-outline-success'" type="button" @click="rateMovie('like', movie.id)">Like</button>
                        <span>|</span>
                        <button class="btn btn-sm" :class="movie.buttonStatus == 'hate' ? 'btn-danger' : 'btn-outline-danger'" type="button" @click="rateMovie('hate', movie.id)">Hate</button>
                    	</div>
                    </div>
                    <div class="col-lg-4 text-end">
                    	<span>Posted by</span>
                      <span class="text-primary cursorShow" @click="filterUser(movie.user_id)">{{creatorFunc(movie)}}</span>
                    </div>
                </div>
            </div>
        </div>
    	</div>
    </div>

    <div class="modal fade" id="movieModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add movie</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-12 py-2">
                    <label>Name</label>
                    <input type="text" v-model="movieModel.name" class="form-control">
                </div>
                <div class="col-12 py-2">
                    <label>Description</label>
                    <textarea class="form-control" v-model="movieModel.description" name="description"></textarea>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click="storeMovie">Add</button>
          </div>
        </div>
      </div>
    </div>

	</div>

</template>

<script>

	export default {

		props: [
			'currentUser',
		],

		data() {
			return {
				movies: [],
				movieModel: '',
				sortBy: '',
				userId: '',
			}
		},

		created() {
			this.getData();
		},

		methods: {

			filterUser(userId) {
				this.userId = userId;
				this.getData();
			},

			creatorFunc(movie) {
				if (movie.user_id == this.currentUser.id) {
					return 'You';
				} else {
					return movie.user.name + ' ' + movie.user.lastName;
				}
			},

			rateMovie(type, movie_id) {
				axios.post('/rateMovie', {
					type: type,
					movie_id: movie_id,
				})
				.then(response => {
	        this.$toasted.show(response.data.message, {
	          type: 'success',
	          duration: 6000,
	        });
	        this.getData();
				})
        .catch(error => {
					helper.axiosCatch(error);
        });
			},

			addMovie() {
				this.movieModel = {};
				Vue.set(this.movieModel, 'name', '');
				Vue.set(this.movieModel, 'description', '');
				$('#movieModal').modal('show');
			},

			storeMovie() {
				axios.post('/movie', {
					name: this.movieModel.name,
					description: this.movieModel.description,
				})
				.then(response => {
	        this.$toasted.show(response.data.message, {
	          type: 'success',
	          duration: 6000,
	        });
					$('#movieModal').modal('hide');
					this.getData();
				})
        .catch(error => {
					helper.axiosCatch(error);
        });
			},

			getData() {
				axios.get('/movies', {
					params: {
						sortBy: this.sortBy,
						user: this.userId,
					},
				})
				.then(response => {
					this.movies = response.data.data;
				})
        .catch(error => {
					helper.axiosCatch(error);
        });
			},

			dateFix(date) {
				moment.locale('en');
				return moment(date).format('DD MMM YYYY');
			},

		},

	}

</script>

<style>
	.cursorShow {
	  cursor: pointer;
	}
</style>