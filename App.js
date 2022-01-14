import React from "react";
import {Image } from "react-native";
import { Asset } from "expo";
import * as Font from 'expo-font'
import AppLoading from "expo-app-loading";
import { Provider } from "react-redux";
import { persistStore } from "redux-persist";
import { PersistGate } from "redux-persist/es/integration/react";
import store from "@store/configureStore";
import RootRouter from "./src/Router";
import "./ReactotronConfig";
import { Config, Images } from './src/common'
import _ from 'lodash'


import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, View } from 'react-native';

function cacheImages(images) {
  return images.map((image) => {
    if (typeof image === "string") {
      return Image.prefetch(image);
    }
    return Asset.fromModule(image).downloadAsync();
  });
}

function cacheFonts(fonts) {
  return fonts.map((font) =>   Font.loadAsync(font));
}

export default class App extends React.Component {
  state = { appIsReady: false };

  loadAssets = async () => {
    var fontAssets
    try{
      fontAssets = cacheFonts([
        { OpenSans: require("@assets/fonts/OpenSans-Regular.ttf") },
        { Baloo: require("./assets/fonts/Baloo-Regular.ttf") },
        { Entypo: require("./assets/fonts/Entypo.ttf") },
        {
          "Material Icons": require("./assets/fonts/MaterialIcons.ttf"),
        },
        {
          MaterialCommunityIcons: require("./assets/fonts/MaterialCommunityIcons.ttf"),
        },
        {
          "Material Design Icons": require("./assets/fonts/MaterialCommunityIcons.ttf"),
        },
        { FontAwesome: require("./assets/fonts/FontAwesome.ttf") },
        {
          "simple-line-icons": require("./assets/fonts/SimpleLineIcons.ttf"),
        },
        { Ionicons: require("./assets/fonts/Ionicons.ttf") },
      ]);
    }
    catch({message}){
      console.log("catch messageis ", message);

    }


    const homeCategories = _.map(Config.HomeCategories, "image");
    var images = _.clone(Images);
    delete images.PlaceHolderURL
    delete images.icons
    delete images.Banner   
    const imageAssets = cacheImages([
      ...homeCategories,
      ...Object.values(Config.Payments),
      ...Object.values(images),
      ...Object.values(Images.icons),
      ...Object.values(Images.Banner),
      Config.LogoImage, Config.LogoWithText, Config.LogoLoading]);

    await Promise.all([...fontAssets, ...imageAssets]); 
  }
  render() {
    const persistor = persistStore(store);
    console.log('Persistor is: ',persistor);  
    if (!this.state.appIsReady) {
      return (
        <AppLoading
          startAsync={this.loadAssets} onError={()=>{console.log("sen olsan bari Error happened ya")}}
          onFinish={() => this.setState({ appIsReady: true })}
        />
      );
    }

    return (
      <Provider store={store}>
        <PersistGate persistor={persistor}>
        <RootRouter />

          {/*<View style={styles.container}>
            <Text>Open up App.js to start working on your apps!</Text>
            <StatusBar style="auto" />
          </View>*/}
        </PersistGate>
      </Provider>

    );

  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
});
