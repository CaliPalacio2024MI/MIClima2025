<?php

namespace Model;


class ResultadosDeptos extends ActiveRecord {

    protected static $tabla = 'resultadosdeptos';
    protected static $columnasDB = ['id', 'cp1', 'cp2', 'cp3', 'cp4', 'cp5', 'cp6', 'cp7', 'cp8', 'cp9', 'cp10', 'cp11', 'cp12', 'cp13', 'cp14', 'cp15', 'cp16', 'cp17', 'cp18', 'cp19', 'cp20', 'cp21', 'cp22', 'cp23', 'cp24', 'cp25', 'cp26', 'cp27', 'cp28', 'cp29', 'cp30', 'cp31', 'cp32', 'cp33', 'cp34', 'cp35', 'cp36', 'cp37', 'cp38', 'cp39', 'cp40',
    'cp41', 'cp42', 'cp43', 'cp44', 'cp45', 'cp46', 'cp47', 'cp48', 'cp49', 'cp50', 'cp51', 'cp52', 'cp53', 'cp54', 'cp55', 'cp56', 'cp57', 'cp58', 'cp59', 'cp60', 'cp61', 'cp62', 'cp63', 'cp64', 'cp65', 'cp66', 'cp67', 'cp68', 'cp69', 'cp70', 'cp71', 'cp72', 'cp73', 'cp74', 'cp75', 'cp76', 'cp77', 'cp78', 'cp79', 'cp80', 'cp81', 'cp82', 'cp83', 'cp84', 'cp85', 'cp86', 'cp87',
    'PlanAC', 'departamentos_id', 'periodos_id', 'idpropiedades'];

    public $id;
    public $cp1;
    public $cp2;
    public $cp3;
    public $cp4;
    public $cp5;
    public $cp6;
    public $cp7;
    public $cp8;
    public $cp9;
    public $cp10;
    public $cp11;
    public $cp12;
    public $cp13;
    public $cp14;
    public $cp15;
    public $cp16;
public $cp17;
public $cp18;
public $cp19;
public $cp20;
public $cp21;
public $cp22;
public $cp23;
public $cp24;
public $cp25;
public $cp26;
public $cp27;
public $cp28;
public $cp29;
public $cp30;
public $cp31;
public $cp32;
public $cp33;
public $cp34;
public $cp35;
public $cp36;
public $cp37;
public $cp38;
public $cp39;
public $cp40;
public $cp41;
public $cp42;
public $cp43;
public $cp44;
public $cp45;
public $cp46;
public $cp47;
public $cp48;
public $cp49;
public $cp50;
public $cp51;
public $cp52;
public $cp53;
public $cp54;
public $cp55;
public $cp56;
public $cp57;
public $cp58;
public $cp59;
public $cp60;
public $cp61;
public $cp62;
public $cp63;
public $cp64;
public $cp65;
public $cp66;
public $cp67;
public $cp68;
public $cp69;
public $cp70;
public $cp71;
public $cp72;
public $cp73;
public $cp74;
public $cp75;
public $cp76;
public $cp77;
public $cp78;
public $cp79;
public $cp80;
public $cp81;
public $cp82;
public $cp83;
public $cp84;
public $cp85;
public $cp86;
public $cp87;
public $PlanAC;
    public $departamentos_id;
    public $periodos_id;
    public $idpropiedades;

    public function __construct($args = []) {
      $this->id = $args['id'] ?? null;
      $this->cp1 = $args['cp1'] ?? '';
      $this->cp2 = $args['cp2'] ?? '';
      $this->cp3 = $args['cp3'] ?? '';
      $this->cp4 = $args['cp4'] ?? '';
      $this->cp5 = $args['cp5'] ?? '';
      $this->cp6 = $args['cp6'] ?? '';
      $this->cp7 = $args['cp7'] ?? '';
      $this->cp8 = $args['cp8'] ?? '';
      $this->cp9 = $args['cp9'] ?? '';
      $this->cp10 = $args['cp10'] ?? '';
      $this->cp11 = $args['cp11'] ?? '';
      $this->cp12 = $args['cp12'] ?? '';
      $this->cp13 = $args['cp13'] ?? '';
      $this->cp14 = $args['cp14'] ?? '';
      $this->cp15 = $args['cp15'] ?? '';
      $this->cp16 = $args['cp16'] ?? '';
      $this->cp17 = $args['cp17'] ?? '';
$this->cp18 = $args['cp18'] ?? '';
$this->cp19 = $args['cp19'] ?? '';
$this->cp20 = $args['cp20'] ?? '';
$this->cp21 = $args['cp21'] ?? '';
$this->cp22 = $args['cp22'] ?? '';
$this->cp23 = $args['cp23'] ?? '';
$this->cp24 = $args['cp24'] ?? '';
$this->cp25 = $args['cp25'] ?? '';
$this->cp26 = $args['cp26'] ?? '';
$this->cp27 = $args['cp27'] ?? '';
$this->cp28 = $args['cp28'] ?? '';
$this->cp29 = $args['cp29'] ?? '';
$this->cp30 = $args['cp30'] ?? '';
$this->cp31 = $args['cp31'] ?? '';
$this->cp32 = $args['cp32'] ?? '';
$this->cp33 = $args['cp33'] ?? '';
$this->cp34 = $args['cp34'] ?? '';
$this->cp35 = $args['cp35'] ?? '';
$this->cp36 = $args['cp36'] ?? '';
$this->cp37 = $args['cp37'] ?? '';
$this->cp38 = $args['cp38'] ?? '';
$this->cp39 = $args['cp39'] ?? '';
$this->cp40 = $args['cp40'] ?? '';
$this->cp41 = $args['cp41'] ?? '';
$this->cp42 = $args['cp42'] ?? '';
$this->cp43 = $args['cp43'] ?? '';
$this->cp44 = $args['cp44'] ?? '';
$this->cp45 = $args['cp45'] ?? '';
$this->cp46 = $args['cp46'] ?? '';
$this->cp47 = $args['cp47'] ?? '';
$this->cp48 = $args['cp48'] ?? '';
$this->cp49 = $args['cp49'] ?? '';
$this->cp50 = $args['cp50'] ?? '';
$this->cp51 = $args['cp51'] ?? '';
$this->cp52 = $args['cp52'] ?? '';
$this->cp53 = $args['cp53'] ?? '';
$this->cp54 = $args['cp54'] ?? '';
$this->cp55 = $args['cp55'] ?? '';
$this->cp56 = $args['cp56'] ?? '';
$this->cp57 = $args['cp57'] ?? '';
$this->cp58 = $args['cp58'] ?? '';
$this->cp59 = $args['cp59'] ?? '';
$this->cp60 = $args['cp60'] ?? '';
$this->cp61 = $args['cp61'] ?? '';
$this->cp62 = $args['cp62'] ?? '';
$this->cp63 = $args['cp63'] ?? '';
$this->cp64 = $args['cp64'] ?? '';
$this->cp65 = $args['cp65'] ?? '';
$this->cp66 = $args['cp66'] ?? '';
$this->cp67 = $args['cp67'] ?? '';
$this->cp68 = $args['cp68'] ?? '';
$this->cp69 = $args['cp69'] ?? '';
$this->cp70 = $args['cp70'] ?? '';
$this->cp71 = $args['cp71'] ?? '';
$this->cp72 = $args['cp72'] ?? '';
$this->cp73 = $args['cp73'] ?? '';
$this->cp74 = $args['cp74'] ?? '';
$this->cp75 = $args['cp75'] ?? '';
$this->cp76 = $args['cp76'] ?? '';
$this->cp77 = $args['cp77'] ?? '';
$this->cp78 = $args['cp78'] ?? '';
$this->cp79 = $args['cp79'] ?? '';
$this->cp80 = $args['cp80'] ?? '';
$this->cp81 = $args['cp81'] ?? '';
$this->cp82 = $args['cp82'] ?? '';
$this->cp83 = $args['cp83'] ?? '';
$this->cp84 = $args['cp84'] ?? '';
$this->cp85 = $args['cp85'] ?? '';
$this->cp86 = $args['cp86'] ?? '';
$this->cp87 = $args['cp87'] ?? '';
 $this->PlanAC = $args['PlanAC'] ?? NULL; 
      $this->departamentos_id = $args['departamentos_id'] ?? '';
      $this->periodos_id = $args['periodos_id'] ?? '';
      $this->idpropiedades = $args['idpropiedades'] ?? '';


       // Inicializa la conexión a la base de datos
   }

   public function actualizarPlanAC($departamentoId, $periodoId, $rutaImagen) {
    $query = "UPDATE resultadosdeptos SET PlanAC = ? WHERE departamentos_id = ? AND periodos_id = ?";
    $stmt = self::$db->prepare($query);

    if ($stmt) {
        $stmt->bind_param("sii", $rutaImagen, $departamentoId, $periodoId);
        $stmt->execute();
        $result = $stmt->affected_rows > 0;
        $stmt->close();
        return $result;
    } else {
        return false;
    }
}

public function obtenerRutaImagen($departamentoId, $periodoId) {
    $query = "SELECT PlanAC FROM resultadosdeptos WHERE departamentos_id = ? AND periodos_id = ?";
    $stmt = self::$db->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ii", $departamentoId, $periodoId);
        $stmt->execute();
        $stmt->bind_result($rutaImagen);
        $stmt->fetch();
        $stmt->close();

        // Verificar si se recuperó algún resultado
        if (isset($rutaImagen)) {
            return $rutaImagen;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

}

