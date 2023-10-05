import { Sequelize, DataTypes } from 'sequelize';
import dotenv from 'dotenv';
dotenv.config();

const { APP_LOCALHOST: hostname, APP_PORT: port, DATABASE: db, USERNAMESQL: username, PASSWORDSQL: mdp } = process.env;

// Create a new instance of Sequelize with your MySQL connection details
const sequelize = new Sequelize('new-liv-v1', 'root', '', {
  host: hostname,
  dialect: 'mysql',
});

// Define the BasicInfo model
const BasicInfo = sequelize.define('basic_info', {
  projectName: {
    type: DataTypes.STRING,
    allowNull: false,
  },
  destFirstName: {
    type: DataTypes.STRING,
    allowNull: false,
  },
  destLastName: {
    type: DataTypes.STRING,
    allowNull: false,
  },
  destAdresse: {
    type: DataTypes.STRING,
    allowNull: false,
  },
  destVille: {
    type: DataTypes.STRING,
    allowNull: false,
  },
  delivererName: {
    type: DataTypes.STRING,
    allowNull: false,
  },
  managerName: {
    type: DataTypes.STRING,
    allowNull: false,
  },
  oldAddress: {
    type: DataTypes.STRING,
    allowNull: true,
  },
  destPhone: {
    type: DataTypes.STRING,
    allowNull: false,
  },
  destIdNumber: {
    type: DataTypes.INTEGER,
    allowNull: false,
  },
  letterType: {
    type: DataTypes.STRING,
    allowNull: false,
  },
});

// Define any associations if needed

export default BasicInfo;
