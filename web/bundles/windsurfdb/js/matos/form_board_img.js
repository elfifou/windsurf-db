(function($) {
	$(document).ready(function() {
		var $container = $('#windsurfdb_matosbundle_board_spec_images');
		var $addLink = $('<button type="button">Ajouter une image</button>');
		$container.prepend($addLink);

		$addLink.click(function(e) {
			addChamp($container);
		});

		var index = $container.find('input').length;

		$container.children('div').each(function() {
			addDeleteLink($(this));
		});

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
}(jQuery));
