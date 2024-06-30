-- Définir les options de transaction
BEGIN;

-- Définir le fuseau horaire
SET TIME ZONE 'UTC';

-- Supprimer la table si elle existe
DROP TABLE IF EXISTS "chall_user";

-- Créer la table `chall_user`
CREATE TABLE IF NOT EXISTS "chall_user" (
                                            "id" SERIAL PRIMARY KEY,
                                            "firstname" VARCHAR(50) NOT NULL,
    "lastname" VARCHAR(50) NOT NULL,
    "email" VARCHAR(320) NOT NULL,
    "password" VARCHAR(255) NOT NULL,
    "role" VARCHAR(50) NOT NULL,
    "status" SMALLINT NOT NULL DEFAULT 0,
    "date_inserted" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "date_updated" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "token" VARCHAR(255) DEFAULT NULL,
    "token_expiry" TIMESTAMP DEFAULT NULL
    );

DROP TABLE IF EXISTS "chall_page";
CREATE TABLE IF NOT EXISTS "chall_page" (
                                            "id" SERIAL PRIMARY KEY,
                                            "title" VARCHAR(50) NOT NULL,
    "description" VARCHAR(350) NOT NULL,
    "content" VARCHAR NOT NULL,
    "user_id" INT NOT NULL,
    "slug" VARCHAR(20) UNIQUE NOT NULL,
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES chall_user(id),
    "date_inserted" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "date_updated" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    );


-- Define the schema for the chall_article table
DROP TABLE IF EXISTS "chall_article";
CREATE TABLE IF NOT EXISTS "chall_article" (
                                               "id" SERIAL PRIMARY KEY,
                                               "title" VARCHAR(50) NOT NULL,
    "description" VARCHAR(350) NOT NULL,
    "content" VARCHAR NOT NULL,
    "user_id" INT NOT NULL,
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES chall_user(id),
    "date_inserted" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "date_updated" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

-- Define the schema for the chall_commentaire table with cascading delete
DROP TABLE IF EXISTS "chall_commentaire";
CREATE TABLE IF NOT EXISTS "chall_commentaire" (
                                                   "id" SERIAL PRIMARY KEY,
                                                   "content" VARCHAR NOT NULL,
                                                   "user_id" INT NOT NULL,
                                                   "article_id" INT NOT NULL,
                                                   "reported" BOOLEAN DEFAULT FALSE;
                                                   FOREIGN KEY (article_id) REFERENCES chall_article(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES chall_user(id),
    "date_inserted" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "date_updated" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    );
DROP TABLE IF EXISTS chall_media;
CREATE TABLE chall_media (
                            id SERIAL PRIMARY KEY,
                            title VARCHAR(80) NOT NULL,
                            lien VARCHAR(1000) NOT NULL,
                            description VARCHAR(100) NOT NULL,
                            date_inserted TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            date_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS chall_config (
                                            id SERIAL PRIMARY KEY,
                                            background_color VARCHAR(7) DEFAULT '#ffffff',
    font_color VARCHAR(7) DEFAULT '#000000',
    font_size VARCHAR(10) DEFAULT '16px',
    font_style VARCHAR(50) DEFAULT 'normal'
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
