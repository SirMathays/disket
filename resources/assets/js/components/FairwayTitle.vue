<template>
	<div class="card-header" v-bind:class="{compact: compact}">
		<div class="p-2">
			<p class="force-white">
				<span class="h2">{{ data.name || 'Uusi kierros' }}</span>
				<a v-if="share" :href="'whatsapp://send?text=' + url" data-action="share/whatsapp/share" style="margin-left: 10px">
					<i class="fab fa-fw fa-whatsapp"></i>
				</a>
				<a v-if="share" href="#" v-clipboard:copy="url">
					<i class="fa fa-fw fa-clipboard"></i>
				</a>
			</p>
			<p style="margin-bottom: 0">{{ Object.values(data.lanes).length }} väylää</p>
		</div>

		<slot name="middle"></slot>

		<slot name="footer"></slot>
	</div>
</template>

<style>
	.force-white > a, 
	.force-white > a:hover, 
	.force-white > a:focus, 
	.force-white > a:active {
		color: #fff;
	}
</style>

<script>
	export default {
		computed: {
			url: function() {
				if(this.share)
					return 'https://disket.app/chart/' + this.share
				else
					return 'https://disket.app'
			}
		},
		props: {
			data: {
				type: Object,
				required: true
			},
			share: {
				type: String,
				required: false
			},
			compact: {
				type: Boolean,
				required: false
			}
		},
	}
</script>