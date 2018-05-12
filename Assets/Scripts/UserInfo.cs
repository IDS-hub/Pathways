using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class UserInfo {

	static UserInfo _instance = null;

	//string _accessToken = "";
	//string _first_name = "";
	//string _last_name = "";
	//string _email = "";

	MiniJsonArray _diagnosis;
	List<string> totalDiagnosis;
	List<string> userAddedDiagnosis;

	public static UserInfo Instance {
		get { 
			if (_instance == null)
				_instance = new UserInfo();

			return _instance;
		}
	}

	public static void Reset() {
		FirstName = "";
		LastName = "";
		Email = "";

		PlayerPrefs.DeleteAll();

		if (Diagnosis != null)
			Diagnosis.Clear();
		
		if (TotalDiagnosis != null)
			TotalDiagnosis.Clear();
		
		if (UserOriginalDiagnosis != null)
			UserOriginalDiagnosis.Clear();

		if (UserAddedDiagnosis != null)
			UserAddedDiagnosis.Clear();
	}

	public static string AccessToken {
		get { 
			return PlayerPrefs.GetString("AccessToken", "");
		}
		set { 
			PlayerPrefs.SetString("AccessToken", value);
			PlayerPrefs.Save();
		}
	}

	public static int CurrentScreen {
		get { 
			return PlayerPrefs.GetInt("CurrentScreen", -1);
		}
		set { 
			PlayerPrefs.SetInt("CurrentScreen", value);
			PlayerPrefs.Save();
		}
	}

	public static bool AvatarEditSkip {
		get { 
			return (PlayerPrefs.GetInt("AvatarEditSkp", 0) == 0 ? false : true);
		}
		set { 
			PlayerPrefs.SetInt("AvatarEditSkp", value ? 1 : 0);
			PlayerPrefs.Save();
		}
	}

	public static bool SessionSkip {
		get { 
			return (PlayerPrefs.GetInt("SessionSkip", 0) == 0 ? false : true);
		}
		set { 
			PlayerPrefs.SetInt("SessionSkip", value ? 1 : 0);
			PlayerPrefs.Save();
		}
	}

	public static bool PainSelectorSkip {
		get { 
			return (PlayerPrefs.GetInt("PainSelectorSkip", 0) == 0 ? false : true);
		}
		set { 
			PlayerPrefs.SetInt("PainSelectorSkip", value ? 1 : 0);
			PlayerPrefs.Save();
		}
	}

	public static string FirstName { set; get; }

	public static string LastName { set; get; }

	public static string Email { set; get; }

	public static bool IsSubscribe { set; get; }

	public static string DailyDose { set; get; }

	public static MiniJsonArray Diagnosis {
		get { 
			return Instance._diagnosis;
		}
		set { 
			Instance._diagnosis = value;
			Instance.totalDiagnosis = new List<string>();
			for (int i = 0; i < value.Count; i++) {
				Instance.totalDiagnosis.Add(value.Get(i).GetField("diagnosis", ""));
			}
		}
	}

	public static List<string> TotalDiagnosis {
		get { 
			return Instance.totalDiagnosis;
		}
	}

	// setting specific camera position for Pain Selector screen
	public static void SetModel() {
		/*bool foundBone = false;
		if (UserOriginalDiagnosis != null && UserOriginalDiagnosis.Count == 1 && AvatarBones != null) {
			string pain = UserOriginalDiagnosis[0];
			for (int i = 0; i < Diagnosis.Count; i++) {
				MiniJsonObject child = Diagnosis.Get(i);
				if (child.GetField("diagnosis", "").Trim() == pain.Trim()) {
					CameraPos = new Vector3(child.GetField("posx", 0f), child.GetField("posy", 0f), child.GetField("posz", 0f));
					CameraRotation = new Vector3(child.GetField("rotx", 0f), child.GetField("roty", 0f), child.GetField("rotz", 0f));
					CameraFov = child.GetField("fov", 0f);
					string boneName = child.GetField("targetBone", "");
					Debug.Log("boneName " + boneName);
					for (int j = 0; j < AvatarBones.Length; j++) {
						if (AvatarBones[j].name == boneName) {
							Debug.Log("found bone " + boneName);
							foundBone = true;
							TargetBone = AvatarBones[j];
							break;
						}
					}
					break;
				}
			}
		}

		if (!foundBone) {
			TargetBone = null;
		}*/

		MiniJsonObject child = SaveCameraPosition;
		if (child != null && AvatarBones != null) {
			CameraPos = new Vector3(child.GetField("posx", 0f), child.GetField("posy", 0f), child.GetField("posz", 0f));
			CameraRotation = new Vector3(child.GetField("rotx", 0f), child.GetField("roty", 0f), child.GetField("rotz", 0f));
			CameraFov = child.GetField("fov", 0f);
			string boneName = child.GetField("targetBone", "");
			Debug.Log("boneName " + boneName);
			for (int j = 0; j < AvatarBones.Length; j++) {
				if (AvatarBones[j].name == boneName) {
					Debug.Log("found bone " + boneName);
					TargetBone = AvatarBones[j];
					break;
				}
			}
		} else
			TargetBone = null;
	}

	// saving specific camera position for Pain Selector screen
	public static MiniJsonObject SaveCameraPosition {
		set {
			if (TargetBone != null) {
				MiniJsonObject position = value;
				position.AddField("posx", CameraPos.x);
				position.AddField("posy", CameraPos.y);
				position.AddField("posz", CameraPos.z);

				position.AddField("rotx", CameraRotation.x);
				position.AddField("roty", CameraRotation.y);
				position.AddField("rotz", CameraRotation.z);

				position.AddField("fov", CameraFov);

				position.AddField("targetBone", TargetBone.name);

				Debug.Log("saving bone name " + position.ToString());

				PlayerPrefs.SetString("SaveCameraPosition", position.ToString());
				PlayerPrefs.Save();
			}
		}
		get{ 
			string data = PlayerPrefs.GetString("SaveCameraPosition", "");
			if (data.Length > 0)
				return new MiniJsonObject(data);

			return null;
		}
	}


	//known diagnosis added by user
	public static List<string> UserOriginalDiagnosis { get; set; }

	public static string UserAddedDiagosisInId {
		get { 
			string names = "";
			if (UserOriginalDiagnosis != null) {
				for (int i = 0; i < UserOriginalDiagnosis.Count; i++) {
					string name = UserOriginalDiagnosis[i];
					for (int j = 0; j < Diagnosis.Count; j++) {
						if (Diagnosis.Get(j).GetField("diagnosis", "") == name) {
							if (i == 0)
								names += Diagnosis.Get(j).GetField("id", "");
							else
								names += "," + Diagnosis.Get(j).GetField("id", "");

							break;
						}
					}
				}
			}

			return names;
		}
	}

	public static string GetDiagnosisNameById(string id) {
		for (int j = 0; j < Diagnosis.Count; j++) {
			if (Diagnosis.Get(j).GetField("id", "") == id) {
				return Diagnosis.Get(j).GetField("diagnosis", "");
			}
		}

		return "";
	}

	// unknown diagnosis added by user
	public static List<string> UserAddedDiagnosis {
		get { 
			return Instance.userAddedDiagnosis;
		}
		set { 
			Instance.userAddedDiagnosis = value;
		}
	}

	public static Vector3 CameraPos { set; get; }

	public static Vector3 CameraRotation { set; get; }

	public static float CameraFov { set; get; }

	public static Transform AvatarHip { set; get; }

	public static Transform AvatarTempHip { set; get; }

	public static Transform HeadEditTargetBone { set; get; }

	public static Transform TargetBone { set; get; }

	public static Transform[] AvatarBones { set; get; }

	public static Avatar UserAvatar { set; get; }

	public static void ParseAvatar(string jsonData) {
		//if (jsonData.Length > 0) {
		List<PainPoint> ps = new List<PainPoint>();
		jsonData = jsonData.hashtableFromJson().toJson();
		//if (!string.IsNullOrEmpty(jsonData) && jsonData != "null") {
				
		MiniJsonObject obj = new MiniJsonObject(jsonData);
		if (obj != null) {
			MiniJsonArray painPointArray = obj.GetJsonArray("painPoints");
			for (int i = 0; i < painPointArray.Count; i++) {
				MiniJsonObject painJson = painPointArray.Get(i);
				PainPoint p = new PainPoint(painJson.GetField("xpos", 0f), painJson.GetField("ypos", 0f), painJson.GetField("zpos", 0f),
					              painJson.GetField("xrot", 0f), painJson.GetField("yrot", 0f), painJson.GetField("zrot", 0f));
				ps.Add(p);
			}

			UserAvatar = new Avatar(obj.GetField("weight", 100f), obj.GetField("figureA", 100f), obj.GetField("age", 100f), obj.GetField("figure", 100f), 
				obj.GetField("eyes_size", 100f), obj.GetField("eye_top_down_size", 100f),
				obj.GetField("jaw_size", 100f), obj.GetField("face_size", 100f), obj.GetField("chin_size", 50f),
				obj.GetField("nose_width", 100f), obj.GetField("nose_height", 100f), obj.GetField("nose_size", 100f),
				obj.GetField("lip_size", 100f), obj.GetField("lip_height", 100f), obj.GetField("lip_width", 100f),
				obj.GetField("eyeColorType", 0), obj.GetField("skinColorType", 0), obj.GetField("hairType", "-1.-1"), obj.GetField("isFemale", false), ps);
		}
	}

	public static List<Session> SessionList { set; get; }

	public static Session CurrentSession { set; get; }

	public static Session NextSession {
		get { 
			if (UserInfo.SessionList != null) {
				for (int i = 0; i < UserInfo.SessionList.Count; i++) {
					if (!UserInfo.SessionList[i].isWatched)
						return UserInfo.SessionList[i];
				}
			}

			return null;
		}
	}

	public static bool IsQuizSession {
		get { 
			if (UserInfo.CurrentSession.id == "17" || UserInfo.CurrentSession.id == "30" ||
			    UserInfo.CurrentSession.id == "44")
				return true;

			return false;
		}
	}

}
