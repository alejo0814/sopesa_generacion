 $rep_diario_save = Reporte_Diario::create([
                'gen_repd_fecha' => $data['fecha'],
                'gen_repd_gen_bruta' => $kw_generados_actual,
                'gen_repd_cons_propio' => 8 * $data['horas_trabajada'],
                'gen_repd_gen_neta' => $kw_generados_actual - ( 8 * $data['horas_trabajada']),
                'gen_repd_cap_nominal' => 2865,
                'gen_repd_cap_efectiva' => 2500,
                'gen_repd_carg_promedio' => $data['horas_trabajada'] != 0 ? $kw_generados_actual / $data['horas_trabajada'] : 0,
                'gen_repd_indice_carg_promed_nominal' => ($data['horas_trabajada'] != 0 && 2865 != 0) ? ($kw_generados_actual / (2865 * $data['horas_trabajada'])) * 100 : 0,
                'gen_repd_hrs_operacion' => $data['horas_trabajada'],
                'gen_repd_hrs_disponibilidad' => $data['horas_disponible'],
                'gen_repd_disp_generador' => 24 != 0 ? ($data['horas_disponible'] / 24) * 100 : 0,
                'gen_repd_cons_combustible_lts' => $acum_comb * 3.7854,
                'gen_repd_cons_combustible_gal' => $acum_comb,
                'gen_repd_efi_comb_bruta' => $kw_generados_actual != 0 ? $acum_comb / $kw_generados_actual : 0,
                'gen_repd_efi_comb_neta' => ($kw_generados_actual - ( 8 * $data['horas_trabajada'])) != 0 ? $acum_comb / ($kw_generados_actual - (8 * $data['horas_trabajada'])) : 0,
                'gen_repd_con_comb_esp_bruto' => $kw_generados_actual != 0 ? (($acum_comb / $kw_generados_actual) * 3.7854 * 0.84 * 1000) : 0,
                'gen_repd_cons_comb_esp_neto' => ($kw_generados_actual - (8 * $data['horas_trabajada'])) != 0 ? (($acum_comb / ($kw_generados_actual - (8 * $data['horas_trabajada']))) * 3.7854 * 0.84 * 1000) : 0,
                'gen_repd_cons_aceite_gal' => $acum_aceite,
                'gen_repd_cons_aceite_lts' => $acum_aceite * 3.7854,
                'gen_repd_hrs_trab_motor_tc' => $hrs_act,
                'gen_repd_hrs_last_overhaul' => 0,
                'gen_repd_hrs_last_mantenimiento' => 0,
                'gen_repd_hrs_trab_ace_lub_motor' => 0,
                'gen_maquina_gen_ma_id' => $data['generador_id']





                
CREATE TABLE aceite_transacciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME NOT NULL,
    tipo ENUM('entrada', 'salida') NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    descripcion TEXT
);


INSERT INTO aceite_transacciones (fecha, tipo, cantidad, descripcion)
VALUES (NOW(), 'entrada', 10.5, 'Ingreso de aceite nuevo');


INSERT INTO aceite_transacciones (fecha, tipo, cantidad, descripcion)
VALUES (NOW(), 'salida', 3.0, 'Uso por máquina A');





CREATE TABLE aceite_almacen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    planta_id INT NOT NULL,
    fecha DATETIME NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    descripcion TEXT,
    FOREIGN KEY (planta_id) REFERENCES plantas(id)
);

SELECT
    (SELECT SUM(cantidad) FROM aceite_almacen WHERE planta_id = 1) -
    (SELECT SUM(cantidad) FROM aceite_consumo WHERE planta_id = 1) AS aceite_disponible;




    
CREATE VIEW vista_aceite_disponible AS
SELECT 
    p.id AS planta_id,
    p.nombre AS planta_nombre,
    IFNULL(ingresos.total_ingresado, 0) - IFNULL(consumos.total_consumido, 0) AS aceite_disponible
FROM plantas p
LEFT JOIN (
    SELECT planta_id, SUM(cantidad) AS total_ingresado
    FROM aceite_almacen
    GROUP BY planta_id
) ingresos ON p.id = ingresos.planta_id
LEFT JOIN (
    SELECT planta_id, SUM(cantidad) AS total_consumido
    FROM aceite_consumo
    GROUP BY planta_id
) consumos ON p.id = consumos.planta_id;












CREATE VIEW vista_aceite_disponible AS
SELECT 
    p.gen_pl_id AS planta_id,
    p.gen_pl_nombre AS planta_nombre,
    IFNULL(ingresos.total_entrada, 0) AS total_entrada,
    IFNULL(consumos.total_consumo, 0) AS total_consumo,
    IFNULL(ingresos.total_entrada, 0) - IFNULL(consumos.total_consumo, 0) AS aceite_disponible
FROM gen_planta p
LEFT JOIN (
    SELECT gen_planta_gen_pl_id, SUM(gen_al_cantidad) AS total_entrada
    FROM gen_aceite_lub
    WHERE gen_al_tipo = 'entrada'
    GROUP BY gen_planta_gen_pl_id
) ingresos ON p.gen_pl_id = ingresos.gen_planta_gen_pl_id
LEFT JOIN (
    SELECT m.gen_planta_gen_pl_id, SUM(r.gen_rea_consumo) AS total_consumo
    FROM gen_registro_aceite r
    JOIN gen_maquina m ON r.gen_maquina_gen_ma_id = m.gen_ma_id
    GROUP BY m.gen_planta_gen_pl_id
) consumos ON p.gen_pl_id = consumos.gen_planta_gen_pl_id;




SELECT * FROM vista_aceite_disponible;





CREATE VIEW vista_aceite_disponible_diario_emd AS
SELECT 
    al.gen_al_fecha AS fecha,
    p.gen_pl_id AS planta_id,
    p.gen_pl_nombre AS planta_nombre,
    IFNULL(SUM(CASE WHEN al.gen_al_tipo = 'entrada' THEN al.gen_al_cantidad ELSE 0 END), 0) AS total_entrada,
    IFNULL((
        SELECT SUM(r.gen_rea_consumo)
        FROM gen_registro_aceite r
        JOIN gen_maquina m ON r.gen_maquina_gen_ma_id = m.gen_ma_id
        WHERE m.gen_planta_gen_pl_id = p.gen_pl_id AND r.gen_rea_fecha = al.gen_al_fecha
    ), 0) AS total_consumo,
    IFNULL(SUM(CASE WHEN al.gen_al_tipo = 'entrada' THEN al.gen_al_cantidad ELSE 0 END), 0) -
    IFNULL((
        SELECT SUM(r.gen_rea_consumo)
        FROM gen_registro_aceite r
        JOIN gen_maquina m ON r.gen_maquina_gen_ma_id = m.gen_ma_id
        WHERE m.gen_planta_gen_pl_id = p.gen_pl_id AND r.gen_rea_fecha = al.gen_al_fecha
    ), 0) AS aceite_disponible,
    CASE 
        WHEN (
            IFNULL(SUM(CASE WHEN al.gen_al_tipo = 'entrada' THEN al.gen_al_cantidad ELSE 0 END), 0) -
            IFNULL((
                SELECT SUM(r.gen_rea_consumo)
                FROM gen_registro_aceite r
                JOIN gen_maquina m ON r.gen_maquina_gen_ma_id = m.gen_ma_id
                WHERE m.gen_planta_gen_pl_id = p.gen_pl_id AND r.gen_rea_fecha = al.gen_al_fecha
            ), 0)
        ) < 50 THEN '⚠️ Nivel bajo'
        ELSE '✅ Nivel adecuado'
    END AS alerta
FROM gen_aceite_lub al
JOIN gen_planta p ON al.gen_planta_gen_pl_id = p.gen_pl_id
WHERE p.gen_pl_nombre = 'EMD'
GROUP BY al.gen_al_fecha, p.gen_pl_id, p.gen_pl_nombre;

