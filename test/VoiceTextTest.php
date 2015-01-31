<?php

require_once dirname(__DIR__) . '/VoiceText.php';

class VoiceTextTest extends PHPUnit_Framework_TestCase
{
    public function setup()
    {
        mb_internal_encoding('UTF-8');
    }
    
    public function testValidate()
    {
        $manager = new VoiceText();
        $errors  = $manager->create(null, array());
        
        // 必須入力チェック
        $this->assertequals($errors[VoiceText::PARAMETER_FILE], '出力するファイル名を必ず入力して下さい');
        $this->assertequals($errors[VoiceText::PARAMETER_TEXT], 'パラメータ[' . VoiceText::PARAMETER_TEXT . ']:必ず入力して下さい');
        $this->assertequals($errors[VoiceText::PARAMETER_SPEAKER], 'パラメータ[' . VoiceText::PARAMETER_SPEAKER . ']:必ず入力して下さい');
        $this->assertequals($errors[VoiceText::PARAMETER_SPEAKER], 'パラメータ[' . VoiceText::PARAMETER_SPEAKER . ']:必ず入力して下さい');
        
        $this->assertArrayNotHasKey(VoiceText::PARAMETER_EMOTION, $errors);
        $this->assertArrayNotHasKey(VoiceText::PARAMETER_EMOTION_LEVEL, $errors);
        $this->assertArrayNotHasKey(VoiceText::PARAMETER_PITCH, $errors);
        $this->assertArrayNotHasKey(VoiceText::PARAMETER_SPEED, $errors);
        $this->assertArrayNotHasKey(VoiceText::PARAMETER_VOLUME, $errors);
        
        // 文字列長チェック
        $parameters = array(VoiceText::PARAMETER_TEXT => 'あああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ');
        $errors  = $manager->create(null, $parameters);
        $this->assertequals($errors[VoiceText::PARAMETER_TEXT], 'パラメータ[' . VoiceText::PARAMETER_TEXT . ']:は' . VoiceText::TEXT_MAX_LENGTH . '文字以内で入力して下さい');
        
        // 不正値チェック
        $parameters = array(
            VoiceText::PARAMETER_EMOTION => 'a',
            VoiceText::PARAMETER_SPEAKER => 'a',
        );
        
        $errors  = $manager->create(null, $parameters);
        $this->assertequals($errors[VoiceText::PARAMETER_EMOTION], 'パラメータ[' . VoiceText::PARAMETER_EMOTION . ']:は' . implode(',', $manager->emotions) . 'から指定して下さい');
        $this->assertequals($errors[VoiceText::PARAMETER_SPEAKER], 'パラメータ[' . VoiceText::PARAMETER_SPEAKER . ']:は' . implode(',', $manager->speakers) . 'から指定して下さい');
        
        
        // 範囲チェック
        $parameters = array(
            VoiceText::PARAMETER_EMOTION_LEVEL => VoiceText::EMOTION_LEVEL_MIN - 1,
            VoiceText::PARAMETER_PITCH         => VoiceText::PITCH_MIN - 1,
            VoiceText::PARAMETER_SPEED         => VoiceText::SPEED_MIN - 1,
            VoiceText::PARAMETER_VOLUME        => VoiceText::VOLUME_MIN - 1,
        );
        
        $errors  = $manager->create(null, $parameters);
        $this->assertequals($errors[VoiceText::PARAMETER_EMOTION_LEVEL], 
            'パラメータ[' . VoiceText::PARAMETER_EMOTION_LEVEL . ']:は' . VoiceText::EMOTION_LEVEL_MIN. '～' . VoiceText::EMOTION_LEVEL_MAX . 'を指定して下さい');
        $this->assertequals($errors[VoiceText::PARAMETER_PITCH], 
            'パラメータ[' . VoiceText::PARAMETER_PITCH . ']:は' . VoiceText::PITCH_MIN. '～' . VoiceText::PITCH_MAX . 'を指定して下さい');
        $this->assertequals($errors[VoiceText::PARAMETER_SPEED], 
            'パラメータ[' . VoiceText::PARAMETER_SPEED . ']:は' . VoiceText::SPEED_MIN. '～' . VoiceText::SPEED_MAX . 'を指定して下さい');
        $this->assertequals($errors[VoiceText::PARAMETER_VOLUME], 
            'パラメータ[' . VoiceText::PARAMETER_VOLUME . ']:は' . VoiceText::VOLUME_MIN. '～' . VoiceText::VOLUME_MAX . 'を指定して下さい');
        
        $parameters = array(
            VoiceText::PARAMETER_EMOTION_LEVEL => VoiceText::EMOTION_LEVEL_MAX + 1,
            VoiceText::PARAMETER_PITCH         => VoiceText::PITCH_MAX + 1,
            VoiceText::PARAMETER_SPEED         => VoiceText::SPEED_MAX + 1,
            VoiceText::PARAMETER_VOLUME        => VoiceText::VOLUME_MAX + 1,
        );
        
        $errors  = $manager->create(null, $parameters);
        $this->assertequals($errors[VoiceText::PARAMETER_EMOTION_LEVEL], 
            'パラメータ[' . VoiceText::PARAMETER_EMOTION_LEVEL . ']:は' . VoiceText::EMOTION_LEVEL_MIN. '～' . VoiceText::EMOTION_LEVEL_MAX . 'を指定して下さい');
        $this->assertequals($errors[VoiceText::PARAMETER_PITCH], 
            'パラメータ[' . VoiceText::PARAMETER_PITCH . ']:は' . VoiceText::PITCH_MIN. '～' . VoiceText::PITCH_MAX . 'を指定して下さい');
        $this->assertequals($errors[VoiceText::PARAMETER_SPEED], 
            'パラメータ[' . VoiceText::PARAMETER_SPEED . ']:は' . VoiceText::SPEED_MIN. '～' . VoiceText::SPEED_MAX . 'を指定して下さい');
        $this->assertequals($errors[VoiceText::PARAMETER_VOLUME], 
            'パラメータ[' . VoiceText::PARAMETER_VOLUME . ']:は' . VoiceText::VOLUME_MIN. '～' . VoiceText::VOLUME_MAX . 'を指定して下さい');
        
        $parameters = array(
            VoiceText::PARAMETER_EMOTION_LEVEL => VoiceText::EMOTION_LEVEL_MAX . 'a',
            VoiceText::PARAMETER_PITCH         => VoiceText::PITCH_MAX . 'a',
            VoiceText::PARAMETER_SPEED         => VoiceText::SPEED_MAX . 'a',
            VoiceText::PARAMETER_VOLUME        => VoiceText::VOLUME_MAX . 'a',
        );
        
        $errors  = $manager->create(null, $parameters);
        $this->assertequals($errors[VoiceText::PARAMETER_EMOTION_LEVEL], 
            'パラメータ[' . VoiceText::PARAMETER_EMOTION_LEVEL . ']:は' . VoiceText::EMOTION_LEVEL_MIN. '～' . VoiceText::EMOTION_LEVEL_MAX . 'を指定して下さい');
        $this->assertequals($errors[VoiceText::PARAMETER_PITCH], 
            'パラメータ[' . VoiceText::PARAMETER_PITCH . ']:は' . VoiceText::PITCH_MIN. '～' . VoiceText::PITCH_MAX . 'を指定して下さい');
        $this->assertequals($errors[VoiceText::PARAMETER_SPEED], 
            'パラメータ[' . VoiceText::PARAMETER_SPEED . ']:は' . VoiceText::SPEED_MIN. '～' . VoiceText::SPEED_MAX . 'を指定して下さい');
        $this->assertequals($errors[VoiceText::PARAMETER_VOLUME], 
            'パラメータ[' . VoiceText::PARAMETER_VOLUME . ']:は' . VoiceText::VOLUME_MIN. '～' . VoiceText::VOLUME_MAX . 'を指定して下さい');
        
        // 任意の1項目以外正常
        $parameters = array(
            VoiceText::PARAMETER_TEXT => 'ああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ',
            VoiceText::PARAMETER_EMOTION => 'happiness',
            VoiceText::PARAMETER_SPEAKER => 'haruka',
            VoiceText::PARAMETER_EMOTION_LEVEL => VoiceText::EMOTION_LEVEL_MAX,
            VoiceText::PARAMETER_PITCH         => VoiceText::PITCH_MAX,
            VoiceText::PARAMETER_SPEED         => VoiceText::SPEED_MAX,
            VoiceText::PARAMETER_VOLUME        => VoiceText::VOLUME_MAX,
        );
        
        $errors  = $manager->create(null, $parameters);
        $this->assertArrayNotHasKey(VoiceText::PARAMETER_TEXT, $errors);
        $this->assertArrayNotHasKey(VoiceText::PARAMETER_EMOTION, $errors);
        $this->assertArrayNotHasKey(VoiceText::PARAMETER_SPEAKER, $errors);
        $this->assertArrayNotHasKey(VoiceText::PARAMETER_EMOTION_LEVEL, $errors);
        $this->assertArrayNotHasKey(VoiceText::PARAMETER_PITCH, $errors);
        $this->assertArrayNotHasKey(VoiceText::PARAMETER_SPEED, $errors);
        $this->assertArrayNotHasKey(VoiceText::PARAMETER_VOLUME, $errors);
        
        unset($parameters[VoiceText::PARAMETER_TEXT]);
        $errors  = $manager->create('test.txt', $parameters);
        $this->assertArrayNotHasKey(VoiceText::PARAMETER_FILE, $errors);
    }

    public function testCreate()
    {
        $file    = 'test.wav';
        $options = array(VoiceText::OPTION_API_KEY => 'API KEY');
        $manager = new VoiceText($options);
        $parameters = array(
            VoiceText::PARAMETER_TEXT => '作成テスト',
            VoiceText::PARAMETER_EMOTION => 'happiness',
            VoiceText::PARAMETER_SPEAKER => 'haruka',
            VoiceText::PARAMETER_EMOTION_LEVEL => VoiceText::EMOTION_LEVEL_MAX,
            VoiceText::PARAMETER_PITCH         => VoiceText::PITCH_MAX,
            VoiceText::PARAMETER_SPEED         => VoiceText::SPEED_MAX,
            VoiceText::PARAMETER_VOLUME        => VoiceText::VOLUME_MAX,
        );
        $result = $manager->create($file, $parameters);
        $this->assertNull($result);

        $options = array(VoiceText::OPTION_API_KEY => 'error');
        $manager = new VoiceText($options);
        $result = $manager->create($file, $parameters);
        $this->assertEquals(key($result), 401); // 認証エラー

        $options = array(VoiceText::OPTION_API_URL => 'error');
        $manager = new VoiceText($options);
        $result = $manager->create($file, $parameters);
        $this->assertEquals(key($result), CURLE_COULDNT_RESOLVE_HOST); // ホスト名が解決出来ない
    }
}
