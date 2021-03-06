<?php
require_once ( 'includes/alllocales.php' );

// Для списка creatureinfo()
$npc_cols [0] = array ( 'name', 'subname', 'minlevel', 'maxlevel', 'type', 'rank', 'A', 'H' );
$npc_cols [1] = array ( 'subname', 'minlevel', 'maxlevel', 'type', 'rank', /*'minhealth', 'maxhealth', 'minmana', 'maxmana',*/ 'mingold', 'maxgold', 'lootid', 'skinloot', 'pickpocketloot', 'spell1', 'spell2', 'spell3', 'spell4', 'A', 'H', 'mindmg', 'maxdmg', 'attackpower', 'dmg_multiplier', /*'armor',*/ 'difficulty_entry_1' );

// Функция информации о создании
function creatureinfo2 ( $Row )
{
    $name_sub_locale = localizedName ( $Row, 'subname' );
    $creature = array ( 'entry' => $Row ['entry'], 'name' => str_replace ( ' (1)', LOCALE_HEROIC, localizedName ( $Row ) ), 'subname' => $name_sub_locale, 'minlevel' => $Row ['minlevel'], 'maxlevel' => $Row ['maxlevel'], 'react' => $Row ['A'] . ',' . $Row ['H'], 'type' => $Row ['type'], 'classification' => $Row ['rank'] );
    
    $x = '';
    $x = '';
    $x .= '';
    $x .= "<table><tr><td><b class=\"q\">" . htmlspecialchars ( str_replace ( ' (1)', LOCALE_HEROIC, localizedName ( $Row ) ) ) . "</b></td></tr></table><table><tr><td>";
    if ( ! empty ( $name_sub_locale ) ) $x .= $name_sub_locale . "<br>";
    $level = ( $Row ['rank'] == 3 ) ? '??' : ( ( $Row ['minlevel'] == $Row ['maxlevel'] ) ? $Row ['minlevel'] : "{$Row ['minlevel']} - {$Row ['maxlevel']}" );
    switch ( $Row ['rank'] )
    {
        case 1:
            $rank = ' '.LOCALE_NPCRANK_ELITE.'';
            break;
        case 2:
            $rank = ' '.LOCALE_NPCRANK_RAREELITE.'';
            break;
        case 3:
            $rank = ' '.LOCALE_NPCRANK_BOSS.'';
            break;
        case 4:
            $rank = ' '.LOCALE_NPCRANK_RARE.'';
            break;
        default:
            $rank = '';
            break;
    }
    
    switch ( $Row ['type'] )
    {
        case 1:
            $type = LOCALE_NPCTYPE_BEAST;
            break;
        case 2:
            $type = LOCALE_NPCTYPE_DRANGONKIN;
            break;
        case 3:
            $type = LOCALE_NPCTYPE_DEMON;
            break;
        case 4:
            $type = LOCALE_NPCTYPE_ELEMENTAL;
            break;
        case 5:
            $type = LOCALE_NPCTYPE_GIANT;
            break;
        case 6:
            $type = LOCALE_NPCTYPE_UNDEAD;
            break;
        case 7:
            $type = LOCALE_NPCTYPE_HUMANOID;
            break;
        case 8:
            $type = LOCALE_NPCTYPE_CRITTER;
            break;
        case 9:
            $type = LOCALE_NPCTYPE_MECHANIC;
            break;
        case 10:
            $type = LOCALE_NPCTYPE_UNCATEGORIZED;
            break;
        default:
            $type = '';
            break;
    }
    
    $x .=  "".LOCALE_LVL." {$level} {$type}{$rank}";
    $x .= "</td></tr></table>";
    
    $creature ['tooltip'] = $x;
    
    return $creature;
}

// Функция информации о создании
function creatureinfo ( $id )
{
    global $DB;
    global $npc_cols;
    $row = $DB->selectRow ( '
			SELECT ?#, c.entry
			{
				, l.name_loc' . $_SESSION ['locale'] . ' as `name_loc`
				, l.subname_loc' . $_SESSION ['locale'] . ' as `subname_loc`
				, ?
			}
			FROM ?_factiontemplate, creature_template c
			{
				LEFT JOIN (locales_creature l)
				ON l.entry=c.entry AND ?
			}
			WHERE
				c.entry = ?d
				AND factiontemplateID = faction_A
			LIMIT 1
		', $npc_cols [0], ( $_SESSION ['locale'] > 0 ) ? 1 : DBSIMPLE_SKIP, ( $_SESSION ['locale'] > 0 ) ? 1 : DBSIMPLE_SKIP, $id );
    return creatureinfo2 ( $row );
}

?>