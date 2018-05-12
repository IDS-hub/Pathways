using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using System.IO;
using System.Text;

public class SaveLoadAudio {

	static string FOLDER_NAME = "Pathways";

	public static void SaveFile(byte[] audio, string filename) {
		
		string filePath = "";
		if (Application.dataPath.Contains("/")) {
			filePath = string.Format("{0}/{1}", Application.persistentDataPath, FOLDER_NAME);
		} else {
			filePath = string.Format("{0}\\{1}", Application.persistentDataPath, FOLDER_NAME);
		}

		if (!Directory.Exists(filePath)) { //create diretory
			Directory.CreateDirectory(filePath);
		}

		FileStream fs = null;
		if (Application.dataPath.Contains("/")) {
			fs = File.Create(string.Format("{0}/{1}.dat", filePath, filename), 512);
		} else {
			fs = File.Create(string.Format("{0}\\{1}.dat", filePath, filename), 512);
		}

		Debug.Log("saved at " + filePath);

		BinaryWriter bw = new BinaryWriter(fs);
		bw.Write(audio);

		bw.Close();
		fs.Close();
	}

	public static string FilePath(string filename) {
		string filePath = "";

		if (Application.dataPath.Contains("/")) {
			filePath = string.Format("{0}/{1}/{2}.dat", Application.persistentDataPath, FOLDER_NAME, filename);
		} else {
			filePath = string.Format("{0}\\{1}\\{2}.dat", Application.persistentDataPath, FOLDER_NAME, filename);
		}

		if (File.Exists(filePath)) {
			filePath = string.Format("file://{0}", filePath);
			return filePath;
		}

		return "";
	}
}
