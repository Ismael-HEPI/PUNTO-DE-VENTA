CREATE DATABASE IF NOT EXISTS db_ventas;

USE db_ventas;

CREATE TABLE usuarios (
	usuario_id int not null auto_increment primary key,
	usuario_nombre varchar(25) not null,
	usuario_apellido varchar(30) not null,
	usuario_direccion varchar(150) not null,
	usuario_telefono varchar(10) not null,
	usuario_correo varchar(50) not null,
	usuario_usuario varchar(30) not null,
	usuario_clave varchar(500) not null,
	usuario_privilegio int(2) not null,
	usuario_estado varchar(9) not null
);

INSERT INTO usuarios(usuario_id, usuario_nombre, usuario_apellido, usuario_direccion, usuario_telefono, usuario_correo, usuario_usuario, usuario_clave, usuario_privilegio, usuario_estado) VALUE(1, "Administrador", "Principal", "Conocido", "9876543210", "Administrador@gmail.com", "Administrador", "OSs2YlNMQ1BML1h3SEQ3Q3Z5YTlMUT09", 1, "Activo");

CREATE TABLE categorias (
    categoria_id int not null auto_increment primary key,
    categoria_nombre VARCHAR(20) NOT NULL,
    categoria_ubicacion VARCHAR(30) NOT NULL
);

CREATE TABLE proveedores (
    proveedor_id int NOT NULL auto_increment PRIMARY KEY, 
    proveedor_nombre varchar(50) NOT NULL,
    proveedor_descripcion varchar(50) NOT NULL
);

CREATE TABLE productos (
    producto_id int not null auto_increment primary key,
	producto_categoria int not null,
    producto_codigo varchar(50) not null ,
    producto_nombre varchar(100) not null,
    producto_descripcion varchar(300) not null,
    producto_stock integer not null check(producto_stock >= 0),
    producto_tipo_unidad varchar(20) not null,
    producto_precio_compra numeric(40, 2) not null,
    producto_precio_venta numeric(40, 2) not null,
    producto_proveedor int not null,
	producto_estado int(2) not null,
    producto_imagen VARCHAR(255) NOT NULL,
    foreign key (producto_categoria) references categorias(categoria_id),
	foreign key (producto_proveedor) references proveedores(proveedor_id)
);

CREATE TABLE clientes (
    cliente_id int not null auto_increment primary key,
    cliente_nombre varchar(25) not null,
    cliente_apellido varchar(25) not null,
    cliente_direccion varchar(150),
    cliente_telefono varchar(10)
);

CREATE TABLE compras (
    compra_id int not null auto_increment primary key,
    compra_cantidad integer not null check(compra_cantidad >= 0),
    compra_total numeric(50, 2) not null check(compra_total >= 0),
    compra_fecha date not null,
    compra_hora time not null,
    producto_id integer references productos(producto_id) on delete cascade,
    categoria_id integer references categorias(categoria_id) on delete cascade,
    proveedor_id integer references proveedores(proveedor_id) on delete cascade
);

   
INSERT INTO productos(producto_id, producto_categoria, producto_codigo, producto_nombre,  producto_descripcion, producto_stock,
producto_tipo_unidad, producto_precio_compra, producto_precio_venta, producto_proveedor, producto_estado) 
VALUE (1, 1, "25252525", "Detergente Roma", "Detergente en polvo para uso multiple" , 6, "Unidades", "15", "21", 1, 1);





SELECT * FROM productos
INNER JOIN categorias ON productos.producto_categoria = categorias.categoria_id
INNER JOIN proveedores ON productos.producto_proveedor = proveedores.proveedor_id;
