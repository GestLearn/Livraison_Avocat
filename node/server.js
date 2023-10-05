import express from "express";

import session from "express-session";
import { fileURLToPath } from 'url';
import route from "./routes/route.js";
import dotenv from 'dotenv';
import path from 'path';


dotenv.config();

const __dirname = path.dirname(fileURLToPath(import.meta.url));

const { APP_LOCALHOST: hostname, APP_PORT: port, SESSION_SECRET } = process.env;

const app = express();

/************* session*/

  app.use(
    session({
      secret: SESSION_SECRET,
      resave: false,
      saveUninitialized: false,
    })
  );
/************  middlewares */
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));
app.use(express.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, "public")));


app.use('/', route);
app.listen(port, hostname, () => {
  console.log(`Server running at http://${hostname}:${port}/`);
});
