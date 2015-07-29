(function($) {
	$(document).ready(function() {
		$('#windsurfdb_matosbundle_board_spec_poids, #windsurfdb_matosbundle_board_spec_techno, #windsurfdb_matosbundle_board_spec_prix, #windsurfdb_matosbundle_board_spec_fin, #windsurfdb_matosbundle_board_spec_box').each(function(i) {
			var $container = $(this);
			var $addLink = $('<button type="button">Ajouter un champ</button>');
			$container.prepend($addLink);

			$addLink.click(function(e) {
				addChamp($container);
			});

			var index = $container.find('input').length;

			if (index == 0 && (i == 0 || i == 1)) {
				addChamp($container);
			} else {
				$container.children('div').each(function() {
					addDeleteLink($(this));
				});
			}

			function addChamp($container) {
				var $prototype = $($container.attr('data-prototype').replace(/__name__/g, index));

				addDeleteLink($prototype);

				$container.append($prototype);

				index++;
			}

			function addDeleteLink($prototype) {
				$deleteLink = $('<button type="button">Supprimer</button>');

				$prototype.append($deleteLink);

				$deleteLink.click(function(e) {
					$prototype.remove();
				});
			}
		});
	});
}(jQuery));
