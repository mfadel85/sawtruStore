/** @format */

import { StyleSheet } from "react-native";

export default StyleSheet.create({
  container: {
    paddingVertical: 15,
    paddingHorizontal: 8,
    alignItems: "flex-start",
    justifyContent: "center",
    width: "100%",
  },
  text: {
    fontSize: 12,
    color: "#2e2e2e",
  },
  selected: (background) => ({
    backgroundColor: background,
  }),
  selectedText: (text) => ({
    fontWeight: "bold",
    color: text,
  }),
});
