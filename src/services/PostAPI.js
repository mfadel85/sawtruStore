/**
 * Created by InspireUI on 13/06/2017.
 *
 * @format
 */

 import WPAPI from "wpapi";
 import { AppConfig } from "../common";
 
 const wpAPI = new WPAPI({
   endpoint: `${AppConfig.Opencart.url}/wp-json`,
 });
 
 export default wpAPI;
 