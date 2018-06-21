<template>
	<div>
		<div class="input-group input-group-lg">
			<input type="text" class="form-control" placeholder="Koodi" @keyup.enter="goTo" v-model="url">
			<div class="input-group-append">
				<b-btn variant="primary" @click="goTo"><i class="fa fa-search"></i></b-btn>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				url: undefined
			}
		},
		methods: {
			goTo() {
				window.location.href = '/chart/' + this.url
			}
		}
	}
</script>