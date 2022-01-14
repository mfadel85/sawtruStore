/** @format */

import React from "react";
import { View } from "react-native";
import LanguagePicker from "./LanguagePicker";
import styles from "./styles";

const Setting = () => {
  return (
    <View style={styles.settingContainer}>
      {/* <RtlSwitch onTintColor={Color.headerTintColor}/> */}
      <LanguagePicker />
    </View>
  );
};
export default Setting;
