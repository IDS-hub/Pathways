using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class TypeWriterEffectConditional : TypeWriterEffect {

	// 3 types of text
	string DIAGNOSIS_0 = "I now realise how many people still needlessly suffer from persistent pain. It’s terrible. For conditions such as RSI, Back Pain, Migraines and Fibromyalgia - once patients understand pain and pain management (without pills), they can turn pain off or in the worst-case scenario, turn pain down.\n\nSo let’s start making progress. I’d like to introduce you to Lisa, your pain therapist. Headphones in!";
	string DIAGNOSIS_2 = "I now realise how many people still needlessly suffer from persistent pain. It’s terrible. For conditions such as RSI, Back Pain, {0}, {1}, Migraines and Fibromyalgia - once patients understand pain and pain management (without pills), they can turn pain off or in the worst-case scenario, turn pain down.\n\nSo let’s start making progress. I’d like to introduce you to Lisa, your pain therapist. Headphones in!";
	string DIAGNOSIS_1 = "I now realise how many people still needlessly suffer from persistent pain. It’s terrible. For conditions such as RSI, Back Pain, {0}, Migraines and Fibromyalgia - once patients understand pain and pain management (without pills), they can turn pain off or in the worst-case scenario, turn pain down.\n\nSo let’s start making progress. I’d like to introduce you to Lisa, your pain therapist. Headphones in!";

	public override void Start() {
		List<string> diagnosis = new List<string>();

		if (UserInfo.UserOriginalDiagnosis != null && UserInfo.UserOriginalDiagnosis.Count > 0) {
			for (int i = 0; i < UserInfo.UserOriginalDiagnosis.Count; i++) {
				string diag = UserInfo.UserOriginalDiagnosis[i];
				if (!diag.Contains("RSI") && !diag.ToUpper().Contains("BACK") && diag != "Migraines" && diag != "Fibromyalgia" && diag != "Repetitive Strain Injury")
					diagnosis.Add(diag);
			}
		}

		if (diagnosis.Count == 1)
			writeText = string.Format(DIAGNOSIS_1, diagnosis[0]);
		else if (diagnosis.Count >= 2)
			writeText = string.Format(DIAGNOSIS_2, diagnosis[0], diagnosis[1]);
		else
			writeText = DIAGNOSIS_0;
		
		base.Start();
	}
}
