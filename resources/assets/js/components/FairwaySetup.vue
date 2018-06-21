<template>
	<div class="card">

		<fairway-title :data="{name: name, lanes: lanes}">
			<transition-group name="fade" slot="middle" class="d-flex flex-wrap justify-content-start">
				<div class="px-2 py-3 text-center" v-for="lane, index in lanes" :key="index">
					<div><b>{{ index+1 }}.</b></div>
					<div>par {{ lane.par }}</div>
				</div>
			</transition-group>

			<div slot="footer" class="mt-5 text-right">
				<a href="#edit" v-smooth-scroll="{ duration: 500, offset: -25 }" class="btn btn-lg btn-outline-light">Muokkaa</a>
				<b-btn variant="light" size="lg" v-b-modal.username>Aloita</b-btn>
			</div>
		</fairway-title>

	    <div class="card-body" id="edit">
			<div class="row">
				<div class="col-sm-12">					
					<h3>Perustiedot</h3>
					<div class="form-group">
						<label>Kierroksen nimi</label>
						<input type="text" class="form-control form-control-lg" v-model="name">
					</div>

					<div class="form-group">
						<label>Oletuspar</label>
						<div class="input-group input-group-lg">
							<div class="input-group-prepend">
								<b-btn variant="primary" @click="defaultPar--"><i class="fa fa-minus"></i></b-btn>
							</div>
							<input type="number" class="text-center form-control" v-model.number="defaultPar" readonly min="1" max="99">
							<div class="input-group-append">
								<b-btn variant="primary" @click="defaultPar++"><i class="fa fa-plus"></i></b-btn>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label>Väylien määrä</label>
						<div class="input-group input-group-lg">
							<div class="input-group-prepend">
								<b-btn variant="primary" @click="laneCount--"><i class="fa fa-minus"></i></b-btn>
							</div>
							<input type="number" class="text-center form-control" v-model.number="laneCount" readonly min="1" max="99">
							<div class="input-group-append">
								<b-btn variant="primary" @click="laneCount++"><i class="fa fa-plus"></i></b-btn>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card-footer">
			<div class="row">
				<div class="col-sm-12">
					<h3>Väylät</h3>
					<transition-group name="lane">
						<div class="form-group" v-for="lane, index in lanes" :key="index">
							<label>
								<span>{{ index+1 }}. väylä</span>
								<a class="float-right" href="#" v-if="lanes[index].edited" @click="e => setPar(e, lanes[index])"><i class="fa fa-undo"></i></a>
							</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<b-btn variant="primary" @click="e => setPar(e, lanes[index], 'minus')"><i class="fa fa-minus"></i></b-btn>
								</div>
								<input type="number" class="text-center form-control" v-model.number="lanes[index].par" min="1" max="99">
								<div class="input-group-append">
									<b-btn variant="primary" @click="e => setPar(e, lanes[index], 'plus')"><i class="fa fa-plus"></i></b-btn>
								</div>
							</div>
						</div>
					</transition-group>
				</div>
			</div>
		</div>

		<b-modal id="username" title="Anna nimesi">
			<div class="form-group">
				<label>Nimi</label>
				<input type="text" class="form-control" v-model="username">
			</div>
			<div slot="modal-footer">
				<b-btn class="float-right" variant="primary" @click="create">Aloita</b-btn>
   			</div>
		</b-modal>
	</div>	
</template>

<style>
	label {
		display: block;
	}

	.fade-enter-active, .fade-leave-active {
		transition: .4s opacity
	}
	.fade-enter, .fade-leave-to {
		opacity: 0;
	}

	.lane-enter-active, .lane-leave-active {
		transition: .4s opacity, .2s margin-top;
	}
	.lane-enter, .lane-leave-to {
		opacity: 0;
		margin-top: -5em;
	}
</style>

<script>
	import FairwayTitle from "./FairwayTitle"

	export default {
		data() {
			return {
				laneCount: 6,
				defaultPar: 3,
				lanes: [],
				name: undefined,
				username: undefined,
			}
		},
		watch: {
			laneCount: function() {
				var lanesLength = Object.values(this.lanes).length
				var newLane = this.defaultLane()

				if(this.laneCount < 1) {
					this.laneCount = 1
				}

				if(lanesLength < this.laneCount) {
					this.lanes.push(Object.assign({}, newLane))
				} else if(lanesLength > this.laneCount) {
					this.lanes.splice(-1)
				}
			},
			defaultPar: function() {
				
				if(this.defaultPar < 1) {
					this.defaultPar = 1
				}

				this.lanes.map((lane, key) => {
					if(!lane.edited) {
						lane.par = this.defaultPar
					}
				})
			}
		},
		methods: {
			defaultLane() {
				return {
					par: this.defaultPar,
					edited: false
				}
			},
			setLanes() {
				var lanesLength = Object.values(this.lanes).length
				var newLane = this.defaultLane()

				while(lanesLength < this.laneCount) {
					lanesLength++
					this.lanes.push(Object.assign({}, newLane))
				}
			},
			setPar(e, lane, plusMinus = undefined) {
				e.preventDefault()

				lane.edited = plusMinus ? true : false

				if(plusMinus == 'plus') lane.par++
				else if(plusMinus == 'minus') lane.par--
				else lane.par = this.defaultPar
			},
			create() {
				var app = this
				app.sending = true

				axios.post('/api/v1/create', {
					lanes: app.lanes,
					name: app.name,
					username: app.username
				}).then(function (response) {
                	window.location.href = response.data.redirect
	            }).catch(error => {
	              console.log(error.response.data);
	              app.sending = false
	            })
			}
		},
		mounted() {
			this.setLanes()
		},
		components: {
			'fairway-title': FairwayTitle,
		}
	}
</script>