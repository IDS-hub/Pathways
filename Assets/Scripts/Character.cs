using System.Collections;
using System.Collections.Generic;
using System.Linq.Expressions;
using UnityEngine;
using System;
using LitJson;
using DG.Tweening;

#region Avatar
[Serializable]
public class Avatar {
	public float weight { get; set; }

	public float figureA { get; set; }

	public float age { get; set; }

	public float figure { get; set; }

	public float eyes_size { get; set; }

	public float eye_top_down_size { get; set; }

	public float jaw_size { get; set; }

	public float face_size { get; set; }

	public float chin_size { get; set; }

	public float nose_width { get; set; }

	public float nose_height { get; set; }

	public float nose_size { get; set; }

	public float lip_size { get; set; }

	public float lip_height { get; set; }

	public float lip_width { get; set; }

	public int eyeColorType { get; set; }

	public int skinColorType { get; set; }

	public string hairType { get; set; }

	public bool isFemale { get; set; }

	public List<PainPoint> painPoints { get; set; }

	public Avatar(float weight, float figureA, float age, float figure, 
	              float eyes_size, float eye_top_down_size, 
	              float jaw_size, float face_size, float chin_size,
	              float nose_width, float nose_height, float nose_size,
	              float lip_size, float lip_height, float lip_width,
	              int eyeColorType, int skinColorType, string hairType, bool isFemale, List<PainPoint> painPoints) {

		this.weight = weight;
		this.figureA = figureA;

		this.age = 100f;

		this.figure = figure;
		this.eyes_size = eyes_size;
		this.eye_top_down_size = eye_top_down_size;

		this.jaw_size = jaw_size;
		this.face_size = face_size;
		this.chin_size = chin_size;

		this.nose_width = nose_width;
		this.nose_height = nose_height;
		this.nose_size = nose_size;

		this.lip_size = lip_size;
		this.lip_height = lip_height;
		this.lip_width = lip_width;

		this.eyeColorType = eyeColorType;
		this.skinColorType = skinColorType;
		this.isFemale = isFemale;
		this.painPoints = painPoints;
		//if(hairType.Length == 0)
		//hairType = "-1.-1";
		this.hairType = hairType;

		Debug.Log("hairType " + hairType);

		if (this.painPoints == null)
			this.painPoints = new List<PainPoint>();
	}

	public Avatar() {
	}
}
#endregion

[Serializable]
public class PainPoint {
	public float xpos { get; set; }

	public float ypos { get; set; }

	public float zpos { get; set; }

	public float xrot { get; set; }

	public float yrot { get; set; }

	public float zrot { get; set; }

	public PainPoint(float xpos, float ypos, float zpos, float xrot, float yrot, float zrot) {
		this.xpos = xpos;
		this.ypos = ypos;
		this.zpos = zpos;
		this.xrot = xrot;
		this.yrot = yrot;
		this.zrot = zrot;
	}
}

// Main character controller
public class Character : MonoBehaviour {
	public static Action<bool,bool> HideCharacter;
	public static Action<bool> DoPowerPose;

	public static Action OnSetModel;

	#region BLEND_SHAPE_VARIABLES

	const int THIN_M = 30;
	const int FAT_M = 31;

	const int SKINNY_M = 37;
	const int MASCULAR_M = 35;

	const int EMACIATED = 34;
	const int HEAVY = 33;

	const int FACE_ROUND_R = 22;
	const int FACE_ROUND_L = 23;
	const int FACE_SQUARE_R = 20;
	const int FACE_SQUARE_L = 21;

	const int CHIN_PLUS_1 = 27;
	const int CHIN_PLUS_2 = 28;
	const int CHIN_PLUS_3 = 17;
	//const int CHIN_MINUS_1 = 7;
	//const int CHIN_MINUS_2 = 29;
	const int CHIN_MINUS_1 = 18;
	const int CHIN_MINUS_2 = 19;

	//	const int JAW_CURVE = 17;
	//	const int JAW_ANGLE = 18;

	const int NOSE_WIDTH_WIDE_M = 8;
	const int NOSE_WIDTH_THIN_M = 11;

	const int NOSE_HEIGHT_PLUS = 4;
	const int NOSE_HEIGHT_MINUS = 10;

	const int NOSE_SIZE_PLUS = 9;
	const int NOSE_SIZE_MINUS = 12;

	const int LIP_SIZE_PLUS = 15;
	const int LIP_SIZE_MINUS = 3;

	const int LIP_HEIGHT_PLUS_1 = 14;
	const int LIP_HEIGHT_PLUS_2 = 16;
	const int LIP_HEIGHT_MINUS_1 = 47;

	const int LIP_WIDHT_PLUS = 13;
	const int LIP_WIDHT_MINUS = 40;

	const int EYE_SIZE = 5;
	const int EYE_SIZE_R = 24;
	const int EYE_SIZE_L = 25;

	const int EYE_TOP = 26;
	const int EYE_DOWN = 6;

	#endregion

	[SerializeField] Transform[] bones;
	[SerializeField] Transform hipBone;
	[SerializeField] Transform tempBone;
	[SerializeField] Transform head_edit_bone;

	[SerializeField] SkinnedMeshRenderer characterRenderer;
	[SerializeField] SkinnedMeshRenderer eyeRenderer;

	[SerializeField] Texture[] eyeMaterials;
	[SerializeField] Color[] skinColors;
	[SerializeField] bool isFemale;

	[SerializeField] Material pointMaterial;

	List<GameObject> painObjects = new List<GameObject>();
	//private GameObject hair;
	private AttachClothes hair;

	#region Delegates

	Quaternion initRotation;
	Vector3 initPosition;

	void OnEnable() {
		//Avatar Customization
		CreationEyeColor.OnPressEyeColor += OnSetEyeColor;
		CreationSkinColor.OnPressSkinColor += OnSetSkinColor;
		CreationHair.OnConfirmHairVarient += OnSetHairColor;

		CreationAvatar.OnSetEyeSize += OnSetEyeSize;
		CreationAvatar.OnSetEyeTopDown += OnSetEyeTopDown;

		CreationAvatar.OnSetFace += OnSetFaceSize;
		CreationAvatar.OnSetJaw += OnSetJawSize;
		CreationAvatar.OnSetChin += OnSetChinSize;

		CreationAvatar.OnSetNoseSize += OnSetNoseSize;
		CreationAvatar.OnSetNoseHeight += OnSetNoseHeight;
		CreationAvatar.OnSetNoseWidth += OnSetNoseWidth;

		CreationAvatar.OnSetLipHeight += OnSetLipHeight;
		CreationAvatar.OnSetLipSize += OnSetLipSize;
		CreationAvatar.OnSetLipWidth += OnSetLipWidth;

		CreationAvatar.OnSetWeight += OnSetWeight;
		CreationAvatar.OnSetHeight += OnSetEmatiated;
		CreationAvatar.OnSetAge += OnSetAge;
		CreationAvatar.OnSetFigure += OnSetFigure;

		MouseOrbitImproved.OnTouchModel += OnTouchModel;
		PainSelector.OnDestroyLastPainPoint += OnDestroyLastPainPoint;
		PainSelector.OnSavePainPoints += OnStartNewSessionButtonClicked;

		SessionWindow.OnRotateModel += OnRotateModel;

		HideCharacter += ShowHideCharacter;
	}

	void OnDisable() {
		CreationEyeColor.OnPressEyeColor -= OnSetEyeColor;
		CreationSkinColor.OnPressSkinColor -= OnSetSkinColor;
		CreationHair.OnConfirmHairVarient -= OnSetHairColor;

		CreationAvatar.OnSetEyeSize -= OnSetEyeSize;
		CreationAvatar.OnSetEyeTopDown -= OnSetEyeTopDown;

		CreationAvatar.OnSetFace -= OnSetFaceSize;
		CreationAvatar.OnSetJaw -= OnSetJawSize;
		CreationAvatar.OnSetChin -= OnSetChinSize;

		CreationAvatar.OnSetNoseSize -= OnSetNoseSize;
		CreationAvatar.OnSetNoseHeight -= OnSetNoseHeight;
		CreationAvatar.OnSetNoseWidth -= OnSetNoseWidth;

		CreationAvatar.OnSetLipHeight -= OnSetLipHeight;
		CreationAvatar.OnSetLipSize -= OnSetLipSize;
		CreationAvatar.OnSetLipWidth -= OnSetLipWidth;

		CreationAvatar.OnSetAge -= OnSetAge;
		CreationAvatar.OnSetWeight -= OnSetWeight;
		CreationAvatar.OnSetHeight -= OnSetEmatiated;
		CreationAvatar.OnSetFigure -= OnSetFigure;

		MouseOrbitImproved.OnTouchModel -= OnTouchModel;
		PainSelector.OnDestroyLastPainPoint -= OnDestroyLastPainPoint;
		PainSelector.OnSavePainPoints -= OnStartNewSessionButtonClicked;

		SessionWindow.OnRotateModel -= OnRotateModel;

		HideCharacter -= ShowHideCharacter;
	}

	#endregion

	void Start() {
		LoadModelProperty();
		initRotation = transform.rotation;
		initPosition = transform.position;
	}

	void LoadModelProperty() {
		Debug.Log("load start");
		ShowHideCharacter(false, false);
		UserInfo.AvatarHip = hipBone;
		UserInfo.AvatarTempHip = tempBone;
		UserInfo.AvatarBones = bones;
		UserInfo.HeadEditTargetBone = head_edit_bone;
		LoadAvatars();
		UserInfo.SetModel();
		Debug.Log("load finish");
	}

	void LoadAvatars() {
		if (UserInfo.UserAvatar == null) {
			UserInfo.UserAvatar = new Avatar();
			UserInfo.UserAvatar.isFemale = isFemale;
		}
		
		SetAvatar();
		SetPainPoints();
	}

	void SetAvatar() {
		
		OnSetAge(UserInfo.UserAvatar.age);
		OnSetWeight(UserInfo.UserAvatar.weight);
		OnSetEmatiated(UserInfo.UserAvatar.figureA);

		OnSetFaceSize(UserInfo.UserAvatar.face_size);
		OnSetJawSize(UserInfo.UserAvatar.jaw_size);
		OnSetChinSize(UserInfo.UserAvatar.chin_size);

		OnSetNoseHeight(UserInfo.UserAvatar.nose_height);
		OnSetNoseSize(UserInfo.UserAvatar.nose_size);
		OnSetNoseWidth(UserInfo.UserAvatar.nose_width);

		OnSetLipHeight(UserInfo.UserAvatar.lip_height);
		OnSetLipSize(UserInfo.UserAvatar.lip_size);
		OnSetLipWidth(UserInfo.UserAvatar.lip_width);

		OnSetSkinColor(UserInfo.UserAvatar.skinColorType);

		OnSetEyeSize(UserInfo.UserAvatar.eyes_size);
		OnSetEyeTopDown(UserInfo.UserAvatar.eye_top_down_size);
		OnSetEyeColor(UserInfo.UserAvatar.eyeColorType);

		Constants.IS_FEMALE = UserInfo.UserAvatar.isFemale;

		if (UserInfo.UserAvatar.hairType != null)
			OnSetHairColor(int.Parse(UserInfo.UserAvatar.hairType.Split('.')[0]), int.Parse(UserInfo.UserAvatar.hairType.Split('.')[1]));

		if (OnSetModel != null)
			OnSetModel();
	}

	void SetPainPoints() {
		if (UserInfo.UserAvatar.painPoints != null) {
			for (int i = 0; i < UserInfo.UserAvatar.painPoints.Count; i++) {
				Vector3 v = new Vector3(UserInfo.UserAvatar.painPoints[i].xpos, UserInfo.UserAvatar.painPoints[i].ypos, UserInfo.UserAvatar.painPoints[i].zpos);
				Vector3 r = new Vector3(UserInfo.UserAvatar.painPoints[i].xrot, UserInfo.UserAvatar.painPoints[i].yrot, UserInfo.UserAvatar.painPoints[i].zrot);
				AddPainPoints(v, Quaternion.Euler(r));
			}
		}
	}

	void OnSetEyeColor(int index) {
		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.eyeColorType = index;
		eyeRenderer.material.mainTexture = eyeMaterials[index];
	}

	void OnSetSkinColor(int index) {
		characterRenderer.material.color = skinColors[index];
		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.skinColorType = index;
	}

	#region Hair

	void OnSetHairColor(int id, int sub_id) {
//		Debug.Log(string.Format("on set hair {0} and {1} ", id, sub_id));
		UserInfo.UserAvatar.hairType = string.Format("{0}.{1}", id, sub_id);
		if (id == -1) {
			DeleteHair();
		}
	
		if (id > -1 && sub_id > -1) {
			DeleteHair();
			//GameObject prop = Resources.Load<GameObject>(string.Format("{0}/{1}_{2}", isFemale ? "Female" : "Male", id, sub_id));
			id = (id * 5) + sub_id;
			GameObject prop = Resources.Load<GameObject>(string.Format("{0}/{1}", isFemale ? "Female" : "Male", id));
			prop = Instantiate<GameObject>(prop);
			hair = prop.GetComponent<AttachClothes>();
			Transform[] bones = hipBone.GetComponentsInChildren<Transform>();
			hair.AttachBones(bones, transform, hipBone, characterRenderer);
		}
	}

	void DeleteHair() {
		if (hair != null)
			Destroy(hair.gameObject);
		hair = null;
	}

	void OnSetEyeSize(float value) {
		if (value <= 100) {
			SetBlendShape(EYE_SIZE_L, 0);
			SetBlendShape(EYE_SIZE_R, 0);
			SetBlendShape(EYE_SIZE, 100 - value);
		} else {
			SetBlendShape(EYE_SIZE_L, value - 100);
			SetBlendShape(EYE_SIZE_R, value - 100);
			SetBlendShape(EYE_SIZE, 0);
		}

		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.eyes_size = value;
	}

	void OnSetEyeTopDown(float value) {
		if (value <= 100) {
			SetBlendShape(EYE_TOP, 0);
			SetBlendShape(EYE_DOWN, 100 - value);
		} else {
			SetBlendShape(EYE_TOP, value - 100);
			SetBlendShape(EYE_DOWN, 0);
		}

		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.eye_top_down_size = value;
	}

	#endregion

	#region Face

	void OnSetFaceSize(float value) {

		if (value <= 100) {
			SetBlendShape(FACE_ROUND_L, 0);
			SetBlendShape(FACE_ROUND_R, 0);
			SetBlendShape(FACE_SQUARE_L, 100 - value);
			SetBlendShape(FACE_SQUARE_R, 100 - value);
		} else {
			SetBlendShape(FACE_ROUND_L, value - 100);
			SetBlendShape(FACE_ROUND_R, value - 100);
			SetBlendShape(FACE_SQUARE_L, 0);
			SetBlendShape(FACE_SQUARE_R, 0);
		}

		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.face_size = value;
	}

	void OnSetJawSize(float value) {
		if (value <= 100) {
			SetBlendShape(CHIN_PLUS_1, 0);
			SetBlendShape(CHIN_PLUS_2, 0);
			SetBlendShape(CHIN_PLUS_3, 0);
			SetBlendShape(CHIN_MINUS_1, 100 - value);
			SetBlendShape(CHIN_MINUS_2, 100 - value);
			//SetBlendShape(JAW_MINUS_2, 100 - value);
		} else {
			SetBlendShape(CHIN_PLUS_1, value - 100);
			SetBlendShape(CHIN_PLUS_2, value - 100);
			SetBlendShape(CHIN_PLUS_3, value - 100);
			SetBlendShape(CHIN_MINUS_1, 0);
			SetBlendShape(CHIN_MINUS_2, 0);
			//SetBlendShape(JAW_MINUS_1, 0);
		}

		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.jaw_size = value;
	}

	void OnSetChinSize(float value) {
		/*if (value <= 100) {
			SetBlendShape(CHIN_DOWN, 0);
			SetBlendShape(CHIN_UP, 100 - value);
		} else {
			SetBlendShape(CHIN_DOWN, value - 100);
			SetBlendShape(CHIN_UP, 0);
		}
			
		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.chin_size = value;*/
	}

	#endregion

	#region Figure

	void OnSetWeight(float value) {
		if (value <= 100) {
			SetBlendShape(FAT_M, 0);
			SetBlendShape(THIN_M, 100 - value);
		} else {
			SetBlendShape(FAT_M, value - 100);
			SetBlendShape(THIN_M, 0);
		}
		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.weight = value;
	}

	void OnSetEmatiated(float value) {
		if (value <= 100) {
			SetBlendShape(HEAVY, 0);
			SetBlendShape(EMACIATED, 100 - value);
		} else {
			SetBlendShape(HEAVY, value - 100);
			SetBlendShape(EMACIATED, 0);
		}
		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.figureA = value;
	}

	void OnSetFigure(float value) {
		if (value <= 100) {
			SetBlendShape(MASCULAR_M, 0);
			SetBlendShape(SKINNY_M, 100 - value);
		} else {
			SetBlendShape(MASCULAR_M, value - 100);
			SetBlendShape(SKINNY_M, 0);
		}
		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.figure = value;
	}

	#endregion

	#region Nose

	void OnSetNoseWidth(float value) {
		if (value <= 100) {
			SetBlendShape(NOSE_WIDTH_WIDE_M, 0);
			SetBlendShape(NOSE_WIDTH_THIN_M, 100 - value);
		} else {
			SetBlendShape(NOSE_WIDTH_WIDE_M, value - 100);
			SetBlendShape(NOSE_WIDTH_THIN_M, 0);
		}
		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.nose_width = value;
	}

	void OnSetNoseHeight(float value) {
		if (value <= 100) {
			SetBlendShape(NOSE_HEIGHT_MINUS, 0);
			SetBlendShape(NOSE_HEIGHT_PLUS, 100 - value);
		} else {
			SetBlendShape(NOSE_HEIGHT_MINUS, value - 100);
			SetBlendShape(NOSE_HEIGHT_PLUS, 0);
		}

		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.nose_height = value;
	}

	void OnSetNoseSize(float value) {
		if (value <= 100) {
			SetBlendShape(NOSE_SIZE_PLUS, 0);
			SetBlendShape(NOSE_SIZE_MINUS, 100 - value);
		} else {
			SetBlendShape(NOSE_SIZE_PLUS, value - 100);
			SetBlendShape(NOSE_SIZE_MINUS, 0);
		}

		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.nose_size = value;
	}

	#endregion

	#region Lips

	void OnSetLipSize(float value) {
		if (value <= 100) {
			SetBlendShape(LIP_SIZE_PLUS, 0);
			//SetBlendShape(LIP_SIZE_PLUS_2, 0);
			SetBlendShape(LIP_SIZE_MINUS, 100 - value);
			//SetBlendShape(LIP_SIZE_MINUS_2, 100 - value);
		} else {
			SetBlendShape(LIP_SIZE_PLUS, value - 100);
			//SetBlendShape(LIP_SIZE_PLUS_2, value - 100);
			SetBlendShape(LIP_SIZE_MINUS, 0);
			//SetBlendShape(LIP_SIZE_MINUS_2, 0);
		}

		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.lip_size = value;
	}

	void OnSetLipHeight(float value) {
		if (value <= 100) {
			SetBlendShape(LIP_HEIGHT_PLUS_1, 0);
			SetBlendShape(LIP_HEIGHT_PLUS_2, 0);
			SetBlendShape(LIP_HEIGHT_MINUS_1, 100 - value);
		} else {
			SetBlendShape(LIP_HEIGHT_PLUS_1, value - 100);
			SetBlendShape(LIP_HEIGHT_PLUS_2, value - 100);
			SetBlendShape(LIP_HEIGHT_MINUS_1, 0);
		}
		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.lip_height = value;
	}

	void OnSetLipWidth(float value) {
		if (value <= 100) {
			SetBlendShape(LIP_WIDHT_PLUS, 0);
			SetBlendShape(LIP_WIDHT_MINUS, 100 - value);
		} else {
			SetBlendShape(LIP_WIDHT_PLUS, value - 100);
			SetBlendShape(LIP_WIDHT_MINUS, 0);
		}
		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.lip_width = value;
	}

	#endregion

	void OnSetAge(float value) {
		/*if (value <= 100) {
			SetBlendShape(AGE_PLUS_1, 0);
			SetBlendShape(AGE_PLUS_2, 0);
			SetBlendShape(AGE_MINUS, 100 - value);
		} else {
			SetBlendShape(AGE_PLUS_1, value - 100);
			SetBlendShape(AGE_PLUS_2, value - 100);
			SetBlendShape(AGE_MINUS, 0);
		}

		if (UserInfo.UserAvatar != null)
			UserInfo.UserAvatar.age = value;*/
	}

	void SetBlendShape(int shapID, float value) {
		if (characterRenderer != null)
			characterRenderer.SetBlendShapeWeight(shapID, value);

		// change hair's blendshape also
		if(hair != null)
			hair.changeBlendShapeTshirt(shapID, value);
	}

	// load pain points on touch
	void OnTouchModel(Vector3 point) {
		GameObject go = Resources.Load<GameObject>("PainPoint");
		go = Instantiate(go, UserInfo.AvatarHip);
//		Material mat = new Material(pointMaterial);
		go.GetComponentInChildren<Decal>().m_Material = new Material(pointMaterial);
		go.transform.position = point;
		go.transform.rotation = Quaternion.LookRotation(Camera.main.transform.position - go.transform.position, Vector3.up);
		painObjects.Add(go.gameObject);
	}

	// load pain points on model load
	void AddPainPoints(Vector3 point, Quaternion rotation) {
		GameObject go = Resources.Load<GameObject>("PainPoint");
		go = Instantiate(go, UserInfo.AvatarHip);
	//	Material mat = new Material(pointMaterial);
		go.GetComponentInChildren<Decal>().m_Material = new Material(pointMaterial);
		go.transform.localPosition = point;
		go.transform.localRotation = rotation;
		painObjects.Add(go.gameObject);
	}

	void OnDestroyLastPainPoint() {
		if (painObjects.Count > 0) {
			GameObject painPoint = painObjects[painObjects.Count - 1];
			painObjects.Remove(painPoint);
			Destroy(painPoint);
		}
	}

	void OnStartNewSessionButtonClicked(APIManager apiManager) {
		if (UserInfo.UserAvatar.painPoints == null)
			UserInfo.UserAvatar.painPoints = new List<PainPoint>();
		UserInfo.UserAvatar.painPoints.Clear();
		for (int i = 0; i < painObjects.Count; i++) {
			PainPoint p = new PainPoint(painObjects[i].transform.localPosition.x, painObjects[i].transform.localPosition.y, painObjects[i].transform.localPosition.z,
				              painObjects[i].transform.localRotation.eulerAngles.x, painObjects[i].transform.localRotation.eulerAngles.y, painObjects[i].transform.localRotation.eulerAngles.z);
			if (UserInfo.UserAvatar == null)
				UserInfo.UserAvatar = new Avatar();
			UserInfo.UserAvatar.painPoints.Add(p);
		}

		string _requestJsonData = JsonMapper.ToJson(UserInfo.UserAvatar);
		apiManager.SaveUserAvatar(_requestJsonData, null);
	}

	private void SliderControllerOnPainIntensityChanged(int value) {
		if (painObjects.Count > 0) {
			painObjects[painObjects.Count - 1].GetComponentInChildren<Decal>().m_Material.color = UIUtilites.GetColorByPainIntensity(value);
		}
	}

	#region Session

	void ShowHideCharacter(bool doHide, bool doAnim) {
		if (doAnim) {
			characterRenderer.enabled = true;
			eyeRenderer.enabled = true;
			if (hair != null)
				hair.gameObject.SetActive(true);
		} else {
			characterRenderer.enabled = !doHide;
			eyeRenderer.enabled = !doHide;
			if (hair != null)
				hair.gameObject.SetActive(!doHide);
		}
	}

	void ResetColor(bool hide){
		Color color = characterRenderer.material.color;
		color.a = hide ? 0 : 1;
		characterRenderer.material.color = color;

		color = eyeRenderer.material.color;
		color.a = hide ? 0 : 1;
		characterRenderer.material.color = color;
	}

	#endregion

	// auto rotate model if back pain founds 
	void OnRotateModel(bool rotate){
//		Debug.Log("rotate " + rotate);
		if (rotate) {
			if (UserInfo.UserAvatar.painPoints != null) {
				for (int i = 0; i < UserInfo.UserAvatar.painPoints.Count; i++) {
					if (UserInfo.UserAvatar.painPoints[i].zpos < 0) {
						// found back pain
						transform.rotation = Quaternion.Euler(new Vector3(0,0,0));
						transform.DORotate(new Vector3(0f, 360f, 0f), 10f, RotateMode.FastBeyond360).SetLoops(-1,LoopType.Restart).SetEase(Ease.Linear);
						break;
					}
				}
			}
		} else {
			transform.DOKill();
			transform.rotation = initRotation;
			transform.position = initPosition;
		}
	}
}
