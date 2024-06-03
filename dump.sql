-- PostgreSQL dump

-- Définir les options de transaction
BEGIN;

-- Définir le fuseau horaire
SET TIME ZONE 'UTC';

-- Créer la table `chall_user`
CREATE TABLE IF NOT EXISTS "chall_user" (
    "id" SERIAL PRIMARY KEY,
    "firstname" VARCHAR(50) NOT NULL,
    "lastname" VARCHAR(50) NOT NULL,
    "email" VARCHAR(320) NOT NULL,
    "password" VARCHAR(255) NOT NULL,
    "status" SMALLINT NOT NULL,
    "date_inserted" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "date_updated" TIMESTAMP NOT NULL DEFAULT '1970-01-01 00:00:00' -- Initialiser avec une date "nulle"
);

-- Définir un déclencheur pour mettre à jour la colonne `date_updated`
CREATE OR REPLACE FUNCTION update_date_updated()
RETURNS TRIGGER AS $$
BEGIN
    NEW.date_updated = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Ajouter le déclencheur à la table `chall_user`
CREATE TRIGGER set_date_updated
BEFORE UPDATE ON "chall_user"
FOR EACH ROW
EXECUTE FUNCTION update_date_updated();

COMMIT;