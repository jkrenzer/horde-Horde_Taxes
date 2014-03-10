-- $Horde: incubator/Horde_Taxes/taxes.sql,v 1.3 2007/05/04 15:23:45 duck Exp $
--
CREATE TABLE horde_taxes (
    id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    value FLOAT NOT NULL,
    sort INT,
    created INT,
    updated INT,
--
  PRIMARY KEY  (id)
);
