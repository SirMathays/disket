<template>
	<div class="card">
		<fairway-title :data="{name: data.name, lanes: data.lanes}" :share="data.secret_key" :compact="true">
		</fairway-title>

		<div v-if="player && lane" class="card-body">
			<div class="d-flex align-items-center justify-content-between">
				<div class="px-2 py-3">
					<a @click="e => navLink(e, prevLane)" href="#" v-bind:class="{disabled: !prevLane}">
						<i class="fa fa-lg fa-arrow-circle-left"></i>
					</a>
				</div>
				<div class="px-2 py-3">
					<h4 class="text-center" style="margin-bottom: 0">{{ lane }}. Väylä</h4>
				</div>
				<div class="px-2 py-3">
					<a @click="e => navLink(e, nextLane)" href="#" v-bind:class="{disabled: !nextLane}">
						<i class="fa fa-lg fa-arrow-circle-right"></i>
					</a>
				</div>
			</div>
			<div class="d-flex justify-content-between form-group">
				<div class="px-2 pt-3 w-100">
					<b-btn :block="true" variant="primary" @click="score = birdie">
						<span class="hideMobile">Birdie</span>
						<span class="badge badge-pill badge-light">{{ birdie }}</span>
					</b-btn>
				</div>
				<div class="px-2 pt-3 w-100">
					<b-btn :block="true" variant="primary" @click="score = currentPar">
						<span class="hideMobile">Par</span>
						<span class="badge badge-pill badge-light">{{ currentPar }}</span>
					</b-btn>
				</div>
				<div class="px-2 pt-3 w-100">
					<b-btn :block="true" variant="primary" @click="score = boogie">
						<span class="hideMobile">Boogie</span>
						<span class="badge badge-pill badge-light">{{ boogie }}</span>
					</b-btn>
				</div>
			</div>
			<div class="form-group px-2">
				<div class="input-group input-group-lg">
					<div class="input-group-prepend">
						<b-btn variant="primary" @click="score--"><i class="fa fa-minus"></i></b-btn>
					</div>
					<input type="number" class="text-center form-control" v-model.number="score" readonly min="1" max="99">
					<div class="input-group-append">
						<b-btn variant="primary" @click="score++"><i class="fa fa-plus"></i></b-btn>
					</div>
				</div>
			</div>
			<div class="form-group px-2 mt-5">
				<b-btn variant="primary" :block="true" @click="save">Tallenna</b-btn>
			</div>
		</div>

		<div v-bind:class="{'card-footer': player && lane, 'card-body': !player || !lane}">
			<h3>Tulostaulukko</h3>
			<div style="overflow-x: auto" class="form-group">
				<div v-for="playerRow in data.players_formatted">
					<p><b>{{playerRow.name}}</b> <span class="badge badge-pill badge-primary">{{ playerRow.total_score }}</span></p>
					<div class="d-flex justify-content-start form-group">
						<div class="lane-square lane-flex" 
							v-bind:class="{'active': player && playerRow.id == player.id && lane == index}" 
							v-for="par, index in data.lanes" 
							@click="e => navLink(e, index, playerRow.id)">

							<div class="lane-id lane-flex">{{ index }}</div>
							<div class="score" v-if="player && playerRow.id == player.id && lane == index">
								{{ score }}
								<span class="badge badge-pill badge-primary">{{ difference }}</span>
							</div>
							<div class="score" v-else-if="playerRow.stats && playerRow.stats[index]">
								{{ playerRow.stats[index] }} 
								<span class="badge badge-pill badge-primary">{{ calcDifference(playerRow.stats[index], par) }}</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group" v-if="!player">
				<b-btn variant="outline-primary" size="lg" v-b-modal.username>Liity</b-btn>
			</div>
		</div>

		<b-modal id="username" title="Anna nimesi">
			<div class="form-group">
				<label>Nimi</label>
				<input type="text" class="form-control" v-model="username">
			</div>
			<div slot="modal-footer">
				<b-btn class="float-right" variant="primary" @click="join">Liity</b-btn>
   			</div>
		</b-modal>
	</div>
</template>

<style>
	.disabled {
		opacity: 0.4;
	}

	@media screen and (max-width:350px) {
		.hideMobile {
			display: none;
		}
	}
</style>

<script>
	import FairwayTitle from "./FairwayTitle"

	export default {
		data() {
			return {
				score: this.player && this.player.stats && this.player.stats[this.lane] ? this.player.stats[this.lane] : 3,
				username: undefined,
			}
		},
		computed: {
			currentPar: function() {
				return this.data.lanes[this.lane]
			},
			totalPar: function() {
				var total = 0
				Object.values(this.data.lanes).map((par, index) => {
					total = total + par
				})

				return total
			},
			difference: function() {
				return this.score - this.currentPar
			},
			nextLane: function() {
				var next = parseInt(this.lane) + 1

				if(this.data.lanes[next])
					return next

				return undefined
			},
			prevLane: function() {
				var prev = parseInt(this.lane) - 1
				
				if(this.data.lanes[prev])
					return prev

				return undefined
			},
			navUrl: function() {
				var rest = '/'

				if(this.player)
					rest = rest + this.player.id

				return '/chart/' + this.data.secret_key + rest
			},
			birdie: function () {
				return this.currentPar - 1
			},
			boogie: function () {
				return this.currentPar + 1
			}
		},
		methods: {
			navLink(e, value, user = null) {
				if(e)
					e.preventDefault()

				var url = this.navUrl

				if(user)
					url = '/chart/' + this.data.secret_key + '/' + user

				if(value)
					window.location.href = url + '?l=' + value
			},
			calcDifference(value, par) {
				return value - par
			},
			save() {
				var app = this
				app.sending = true

				axios.post('/api/v1/save-lane', {
					chart: app.data.id,
					lane: app.lane,
					player: app.player.id,
					score: app.score
				}).then(function (response) {
					if(app.nextLane) 
						app.navLink(null, app.nextLane)
					else 
						window.location.href = "/chart/" + app.data.secret_key
	            }).catch(error => {
	              console.log(error.response.data);
	              app.sending = false
	            })
			},
			join() {
				var app = this
				app.sending = true

				axios.post('/api/v1/join', {
					chart: app.data.id,
					username: app.username,
				}).then(function (response) {
					window.location.href = response.data.redirect
				}).catch(error => {
					console.log(error.response.data);
					app.sending = false
				})
			}
		},
		watch: {
			score: function() {
				if(this.score < 1) {
					this.score = 1
				}
			},
		},
		props: {
			data: {
				type: Object,
				required: true
			},
			player: {
				type: Object,
				required: false
			},
			lane: {
				type: String,
				required: false,
			}
		},
		components: {
			'fairway-title': FairwayTitle,
		}
	}
</script>