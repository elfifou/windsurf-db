(function($) {
	$(document).ready(function() {
		$('select[name="marques"]').change(function() {
			var val = $(this).find(':selected').val();
			$.ajax({
				dataType: "json",
				method: "POST",
				url: "ajax/board_index",
				data: { marque_id: val }
			})
			.done(function(data) {
				var content_modeles = '<option value="0">Tous</option>';
				$.each(data.modeles, function(i, v) {
					content_modeles += '<option value="'+v+'">'+v+'</option>';
				});
				$('select[name="modeles"]').html(content_modeles);

				var content_annees = '<option value="0">Toutes</option>';
				$.each(data.annees, function(i, v) {
					content_annees += '<option value="'+v+'">'+v+'</option>';
				});
				$('select[name="annees"]').html(content_annees);
			})
			.fail(function(data) {
				console.log(data.responseText)
			});
		});
		$('select[name="modeles"]').change(function() {
			var marque_id = $('select[name="marques"]').find(':selected').val();
			var modele_name = $(this).find(':selected').val();
			$.ajax({
				dataType: "json",
				method: "POST",
				url: "ajax/board_index",
				data: { marque_id: marque_id, modele_name: modele_name }
			})
			.done(function(data) {
				var content_annees = '<option value="0">Toutes</option>';
				$.each(data.annees, function(i, v) {
					content_annees += '<option value="'+v+'">'+v+'</option>';
				});
				$('select[name="annees"]').html(content_annees);

				hydrate_array(data);
			})
			.fail(function(data) {
				console.log(data.responseText)
			});
		});
		$('select[name="annees"]').change(function() {
			var marque_id = $('select[name="marques"]').find(':selected').val();
			var modele_name = $('select[name="modeles"]').find(':selected').val();
			var annee = $(this).find(':selected').val();
			$.ajax({
				dataType: "json",
				method: "POST",
				url: "ajax/board_index",
				data: { marque_id: marque_id, modele_name: modele_name, annee: annee }
			})
			.done(function(data) {
				hydrate_array(data);
			})
			.fail(function(data) {
				console.log(data.responseText)
			});
		});

		function hydrate_array(data) {
			var content_specs = '';
			var techno_flag = false;
			$.each(data.spec, function(i, v) {
				var poids = '';
				$.each(v.poids, function(i, val) {
					if(val != '' && val != undefined) {
						var techno = '';
						if(v.techno[i] != null && v.techno[i].length != 0 && v.techno[i] != undefined) {
							techno = v.techno[i] + ': ';
							techno_flag = true;
						}
						poids += techno+val+' kg<br>';
					}
				});
				var prix = '';
				$.each(v.prix, function(i, val) {
					if(val != '' && val != undefined) {
						prix += val+'<br>';
					}
				});

				var fin = '';
				$.each(v.fin, function(i, val) {
					if(val != '' && val != undefined) {
						fin += val;
					}
					if(v.box[i] != undefined) {
						fin += ' ('+v.box[i]+')';
					}
					fin += '<br>';
				});

				if(v.smodele == undefined) {
					v.smodele = '';
				}

				var voileMini = '';
				if(v.voileMini != null) {
					voileMini = v.voileMini;
				}
				var voileMaxi = '';
				if(v.voileMaxi != null) {
					voileMaxi = v.voileMaxi;
				}

				content_specs += '<tr>';
				content_specs += '<td class="img" data-img="'+v.url+'"><img src="'+v.url+'" alt="'+v.alt+'"></td>';
				content_specs += '<td>'+v.marque+'</td>';
				content_specs += '<td>'+v.modele+' '+v.smodele+' '+v.annee+'</td>';
				content_specs += '<td>'+v.volume+' L</td>';
				content_specs += '<td>'+v.longueur+'</td>';
				content_specs += '<td>'+v.largeur+'</td>';
				content_specs += '<td>'+poids+'</td>';
				content_specs += '<td>'+v.programme+'</td>';
				content_specs += '<td>'+fin+'</td>';
				content_specs += '<td>'+voileMini+' - '+voileMaxi+'</td>';
				content_specs += '<td>'+prix+'</td>';
				content_specs += '</tr>';

			});
			if(!techno_flag) {
				$('th.poids').html('Poids');
			}
			else {
				$('th.poids').html('Poids <small>(Techno)</small>');
			}
			$('tbody').html(content_specs);
			$('tbody td.img').click(function() {
				alert($(this).data('img'))
			});
		}
	});
}(jQuery));
