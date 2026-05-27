CREATE TABLE carros (
    id INTEGER NOT NULL AUTO_INCREMENT,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    qtd_paginas INTEGER NOT NULL,
    cambio VARCHAR(1) NOT NULL,
    combustivel VARCHAR(1) NOT NULL,
    CONSTRAINT pk_carros PRIMARY KEY (id)
);
ALTER TABLE carros 
CHANGE qtd_paginas ano_fabricacao INTEGER NOT NULL;
ALTER TABLE carros ADD COLUMN 
imagem VARCHAR(2000) NOT NULL DEFAULT 'Não Informado';