import { Sequelize, DataTypes } from 'sequelize';

// dashboardController.js
import BasicInfo from '../models/basicInfo.model.js'; // Adjust the path as needed

export const dashboard = async (req, res) => {
  try {
    // Fetch data from the BasicInfo model
    const basicInfoData = await BasicInfo.findAll();

    // Render the 'index.ejs' view with the data
    res.render('index2', { basicInfoData });
  } catch (error) {
    console.error('Error fetching data:', error);
    res.status(500).send('Error fetching data from the database');
  }
};
