 $(document).ready(function() {
  // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
  var $container = $('div#windsurfdb_matosbundle_marque_url');

  // On ajoute un lien pour ajouter une nouvelle url
  var $addLink = $('<button type="button">Ajouter une url</button>');
  $container.append($addLink);

  // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
  $addLink.click(function(e) {
    addCategory($container);
  });

  // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
  var index = $container.find('input').length;

  // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
  if (index == 0) {
    addCategory($container);
  } else {
    // Pour chaque url déjà existante, on ajoute un lien de suppression
    $container.children('div').each(function() {
      addDeleteLink($(this));
    });
  }

  // La fonction qui ajoute un formulaire Categorie
  function addCategory($container) {
    // Dans le contenu de l'attribut « data-prototype », on remplace :
    // - le texte "__name__label__" qu'il contient par le label du champ
    // - le texte "__name__" qu'il contient par le numéro du champ
    var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'Url n°' + (index+1)).replace(/__name__/g, index));

    // On ajoute au prototype un lien pour pouvoir supprimer la url
    addDeleteLink($prototype);

    // On ajoute le prototype modifié à la fin de la balise <div>
    $container.append($prototype);

    // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
    index++;
  }

  // La fonction qui ajoute un lien de suppression d'une url
  function addDeleteLink($prototype) {
    // Création du lien
    $deleteLink = $('<button type="button">Supprimer</button>');

    // Ajout du lien
    $prototype.append($deleteLink);

    // Ajout du listener sur le clic du lien
    $deleteLink.click(function(e) {
      $prototype.remove();
    });
  }
});
