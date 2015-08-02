(function($) {
	$(document).ready(function() {
		$('button#add_techno').click(function() {
			nb_techno++;
			var $html = $('<tr class="s-spec" id="t_'+nb_techno+'"><td>S-spec '+(nb_techno + 1)+'<div><button type="button" class="delete">Supprimer</button></div></td><td><table><tbody><tr><td><label>Techno</label></td><td><input type="text" id="windsurfdb_matosbundle_board_spec_techno_'+nb_techno+'" name="windsurfdb_matosbundle_board_spec[techno]['+nb_techno+']"></td></tr><tr><td><label>Poids</label></td><td><input type="text" id="windsurfdb_matosbundle_board_spec_poids_'+nb_techno+'" name="windsurfdb_matosbundle_board_spec[poids]['+nb_techno+']"></td></tr><tr><td><label>Prix</label></td><td><input type="text" id="windsurfdb_matosbundle_board_spec_prix_'+nb_techno+'" name="windsurfdb_matosbundle_board_spec[prix]['+nb_techno+']"></td></tr><tr><td><label>Box</label></td><td><input type="text" id="windsurfdb_matosbundle_board_spec_box_'+nb_techno+'" name="windsurfdb_matosbundle_board_spec[box]['+nb_techno+']"></td></tr><tr><td><label>Fin</label></td><td><input type="text" id="windsurfdb_matosbundle_board_spec_fin_'+nb_techno+'" name="windsurfdb_matosbundle_board_spec[fin]['+nb_techno+']"></td></tr></tbody></table></td></tr>');
			$html.insertBefore('.infos');
			$('#t_'+nb_techno+' button.delete').click(function() {
				$(this).parent().parent().parent().remove();
			});
		});
	});
}(jQuery));
