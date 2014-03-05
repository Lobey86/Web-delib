<?php
echo $this->Html->script('jstree.min');
echo $this->Html->css('jstree/style.min');
echo $this->Html->css('treeview');
?>
<h2>Liste des profils</h2>
<div class="input-append">
    <input type="text" id="search_tree" placeholder="Filtrer par nom" style="float: left; z-index: 2"/>

    <div class="btn-group" id="search_bloc">
        <a class="btn" id="search_tree_button" title="Lancer la recherche">
            <i class="fa fa-search"></i>
        </a>

        <a class="btn dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </a>

        <ul class="dropdown-menu">
            <li>
                <a id="search_tree_erase_button" title="Remettre à zéro la recherche">Effacer la recherche</a>
            </li>
            <li class="divider"></li>
            <li>
                <a id="search_tree_plier_button" title="Replier tous les profils">Tout replier</i></a>
            </li>
            <li>
                <a id="search_tree_deplier_button" title="Déplier tous les profils">Tout déplier</a>
            </li>
        </ul>
    </div>
</div>
<div id="boutons_action">
    <?php
    echo $this->Html->link('<i class="fa fa-plus"></i> Nouveau', array('action' => 'add'), array('escape' => false, 'id' => 'boutonAdd', 'class' => 'btn btn-primary'));
    echo $this->Html->link('<i class="fa fa-edit"></i> Modifier', '#', array('escape' => false, 'id' => 'boutonEdit', 'class' => 'btn btn-warning'));
    echo $this->Html->link('<i class="fa fa-trash-o"></i> Supprimer', '#', array('escape' => false, 'id' => 'boutonDelete', 'class' => 'btn btn-danger'));
    echo $this->Html->link('<i class="fa fa-envelope"></i> Envoyer un mail', '#', array('escape' => false, 'id' => 'boutonMail', 'class' => 'btn btn-info'));
    ?>
</div>
<div class="spacer"></div>

<div id="arbre">
    <?php
    echo $this->Tree->generateIndex($data, 'Profil', array('id' => 'id', 'display' => 'libelle'));
    ?>
</div>

<script>
    function addAction() {
        window.location.href = $('a#boutonAdd').attr("href");
    }
    function editAction() {
        window.location.href = $('a#boutonEdit').attr("href");
    }
    function deleteAction() {
        window.location.href = $('a#boutonDelete').attr("href");
    }
    function mailAction() {
        window.location.href = $('a#boutonMail').attr("href");
    }
    $(document).ready(function () {
        var jstreeconf = {
            /* Initialisation de jstree sur la liste des profils */
            "core": { //Paramétrage du coeur du plugin
                "multiple": false,
                "themes": { "stripes": true } //Une ligne sur deux est grise (meilleure lisibilité)
            },
            "checkbox": { //Paramétrage du plugin checkbox
                "three_state": false //Ne pas propager la séléction parent/enfants
            },
            "search": { //Paramétrage du plugin de recherche
                "fuzzy": false, //Indicates if the search should be fuzzy or not (should chnd3 match child node 3).
                "show_only_matches": true, //Masque les résultats ne correspondant pas
                "case_sensitive": false //Sensibilité à la casse
            },
            "types": {
                "level0": {
                    "icon": "fa fa-sitemap"
                },
                "default": {
                    "icon": "fa fa-users"
                }
            },
            "contextmenu": {
                "items": contextMenu
            },
            "plugins": [
                "checkbox", //Affiche les checkboxes
                "wholerow", //Toute la ligne est surlignée
                "search", //Champs de recherche d'élément de la liste (filtre)
                "types",
                "contextmenu"
            ]
        };

        $('#arbre').jstree(jstreeconf);

        $("a#boutonEdit").hide();
        $("a#boutonDelete").hide();
        $("a#boutonMail").hide();
        $('#arbre').on('changed.jstree', function (e, data) {
            /* Listener onChange qui fait la synchro jsTree/hiddenField */
            $("a#boutonEdit").hide().prop('href','#');
            $("a#boutonDelete").hide().prop('href','#');
            $("a#boutonMail").hide().prop('href','#');
            if (data.selected.length) {
                var node = data.instance.get_node(data.selected);
                $("a#boutonEdit").show().prop('href', '/profils/edit/' + data.instance.get_node(data.selected).data.id)
                $("a#boutonMail").show().prop('href', '/profils/notifier/' + data.instance.get_node(data.selected).data.id)
                if ($('#' + node.id).hasClass('deletable')) {
                    $("a#boutonDelete").show().prop('href', '/profils/delete/' + data.instance.get_node(data.selected).data.id)
                }
            }
        });

        /* Recherche dans la liste jstree */
        $('#search_tree_button').click(function () {
            $('#arbre').jstree(true).search($('#search_tree').val());
        });

        $('#search_tree_erase_button').click(function () {
            $('#search_tree').val('');
            $('#search_tree_button').click();
        });

        $('#search_tree').keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                $('#search_tree_button').click();
                return false;
            }
        });
        $("#search_tree_plier_button").click(function () {
            $('#arbre').jstree('close_all');
        });
        $("#search_tree_deplier_button").click(function () {
            $('#arbre').jstree('open_all');
        });
    });

    function contextMenu(node) {
        // The default set of all items
        var items = {
            "add": {
                "label": "Nouveau",
                "action": addAction,
                "icon": "fa fa-plus",
                "separator_after": true
            },
            "edit": {
                "label": "Modifier",
                "action": editAction,
                "icon": "fa fa-edit"
            },
            "delete": {
                "label": "Supprimer",
                "action": deleteAction,
                "icon": "fa fa-trash-o"
            },
            "mail": {
                "separator_before": true,
                "label": "Envoyer un mail",
                "action": mailAction,
                "icon": "fa fa-envelope"
            }
        };
        if ($('#'+node.id).hasClass("deletable") == false) {
            delete items.delete;
        }
        return items;
    }
</script>